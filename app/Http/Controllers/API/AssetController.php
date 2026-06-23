<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AssetController extends Controller
{
  
    public function index()
    {
        $user = auth()->user();

        return response()->json(
            Asset::with(['category', 'subCategory', 'branch', 'room'])
                ->where('company_id', $user->company_id)   // ← filter langsung by company_id
                ->latest()
                ->get()
        );
    }

    public function show($id)
    {
        $user  = auth()->user();
        $asset = Asset::with(['category', 'subCategory', 'branch', 'room'])
            ->where('company_id', $user->company_id)
            ->findOrFail($id);

        return response()->json($asset);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
        ]);

        $data = $request->all();
        $data['company_id'] = auth()->user()->company_id; // ← paksa company_id dari token

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('assets', 'public');
        }

        $asset = Asset::create($data);

        return response()->json([
            'message' => 'Asset berhasil ditambahkan',
            'data'    => $asset->load(['category', 'subCategory', 'branch', 'room']),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user  = auth()->user();
        $asset = Asset::where('company_id', $user->company_id)->findOrFail($id);

        $data = $request->except(['_method', '_token']);

        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($asset->photo) Storage::disk('public')->delete($asset->photo);
            $data['photo'] = $request->file('photo')->store('assets', 'public');
        }

        $asset->update($data);

        return response()->json(['message' => 'Asset berhasil diupdate']);
    }

    public function destroy($id)
    {
        $user  = auth()->user();
        $asset = Asset::where('company_id', $user->company_id)->findOrFail($id);

        if ($asset->photo) Storage::disk('public')->delete($asset->photo);
        $asset->delete();

        return response()->json(['message' => 'Asset berhasil dihapus']);
    }

    public function export()
    {
        $user   = auth()->user();
        $assets = Asset::with(['branch', 'category', 'subCategory', 'room'])
            ->where('company_id', $user->company_id)
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Nama Aset', 'Cabang', 'Ruangan', 'Kategori', 'Sub Kategori', 'Kondisi', 'Jumlah', 'Brand', 'Tahun', 'Nilai'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i);
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
            ]);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach ($assets as $i => $a) {
            $row = $i + 2;
            $sheet->setCellValue('A' . $row, $a->name);
            $sheet->setCellValue('B' . $row, $a->branch->name       ?? '-');
            $sheet->setCellValue('C' . $row, $a->room->name         ?? $a->room ?? '-');
            $sheet->setCellValue('D' . $row, $a->category->name     ?? '-');
            $sheet->setCellValue('E' . $row, $a->subCategory->name  ?? '-');
            $sheet->setCellValue('F' . $row, ucfirst($a->condition  ?? '-'));
            $sheet->setCellValue('G' . $row, $a->quantity           ?? 1);
            $sheet->setCellValue('H' . $row, $a->brand              ?? '-');
            $sheet->setCellValue('I' . $row, $a->acquisition_year   ?? '-');
            $sheet->setCellValue('J' . $row, $a->value ? 'Rp ' . number_format($a->value, 0, ',', '.') : '-');
        }

        $filename = 'Data_Aset_' . now()->format('Ymd_His') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Nama Aset', 'Kondisi', 'Brand', 'Tahun', 'Nilai', 'Jumlah', 'Cabang (nama)', 'Kategori (nama)', 'Sub Kategori (nama)'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i);
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']],
            ]);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setCellValue('A2', 'Laptop Dell');
        $sheet->setCellValue('B2', 'baik');
        $sheet->setCellValue('C2', 'Dell');
        $sheet->setCellValue('D2', '2023');
        $sheet->setCellValue('E2', '15000000');
        $sheet->setCellValue('F2', '2');
        $sheet->setCellValue('G2', 'Kantor Pusat');
        $sheet->setCellValue('H2', 'Elektronik');
        $sheet->setCellValue('I2', 'Laptop');

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_Aset.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'File tidak ditemukan'], 400);
        }

        $user = auth()->user();
        $file = $request->file('file')->store('imports', 'local');
        $path = storage_path('app/' . $file);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $rows        = $spreadsheet->getActiveSheet()->toArray();

        $imported = 0;
        $errors   = [];

        foreach ($rows as $i => $row) {
            if ($i === 0 || empty($row[0])) continue;

            [$name, $condition, $brand, $year, $value, $qty, $branchName, $categoryName, $subCategoryName] = array_pad($row, 9, null);

            if (!$name || !$condition) {
                $errors[] = "Baris " . ($i + 1) . ": Nama dan Kondisi wajib diisi";
                continue;
            }
            $branch      = $branchName      ? \App\Models\Branch::where('name', $branchName)->where('company_id', $user->company_id)->first() : null;
            $category    = $categoryName    ? \App\Models\AssetCategory::where('name', $categoryName)->first() : null;
            $subCategory = $subCategoryName ? \App\Models\AssetSubCategory::where('name', $subCategoryName)->first() : null;

            Asset::create([
                'company_id'       => $user->company_id,
                'name'             => $name,
                'condition'        => $condition,
                'brand'            => $brand,
                'acquisition_year' => $year,
                'value'            => $value,
                'quantity'         => $qty ?? 1,
                'branch_id'        => $branch?->id,
                'category_id'      => $category?->id,
                'sub_category_id'  => $subCategory?->id,
            ]);

            $imported++;
        }

        \Illuminate\Support\Facades\Storage::disk('local')->delete($file);

        return response()->json([
            'message' => "$imported aset berhasil diimport",
            'errors'  => $errors,
        ]);
    }
}