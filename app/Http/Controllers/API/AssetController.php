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
    return response()->json(
        Asset::with(['category','subCategory','branch'])->latest()->get()
    );
}

    public function store(Request $request)
{
    $data = $request->all();

    // upload foto
    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('assets', 'public');
    }

    $asset = Asset::create($data);

    return response()->json([
        'message' => 'Asset berhasil ditambahkan',
        'data' => $asset
    ]);
}

public function update(Request $request, $id)
{
    $asset = Asset::findOrFail($id);
    $data = $request->all();

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('assets', 'public');
    }

    $asset->update($data);

    return response()->json([
        'message' => 'Asset berhasil diupdate'
    ]);
}
public function importTemplate()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Nama Aset', 'Lokasi', 'Kondisi', 'Brand', 'Tahun', 'Nilai', 'Cabang (nama)', 'Kategori (nama)', 'Sub Kategori (nama)'];
    foreach ($headers as $i => $h) {
        $col = chr(65 + $i);
        $sheet->setCellValue($col . '1', $h);
        $sheet->getStyle($col . '1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']],
        ]);
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Contoh data
    $sheet->setCellValue('A2', 'Laptop Dell');
    $sheet->setCellValue('B2', 'Ruang IT');
    $sheet->setCellValue('C2', 'baik');
    $sheet->setCellValue('D2', 'Dell');
    $sheet->setCellValue('E2', '2023');
    $sheet->setCellValue('F2', '15000000');
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

    $file = $request->file('file')->store('imports', 'local');
    $path = storage_path('app/' . $file);

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $imported = 0;
    $errors = [];

    foreach ($rows as $i => $row) {
        if ($i === 0) continue; // skip header
        if (empty($row[0])) continue; // skip baris kosong

        [$name, $location, $condition, $brand, $year, $value, $branchName, $categoryName, $subCategoryName] = array_pad($row, 9, null);

        // Cari relasi by name
        $branch   = $branchName   ? \App\Models\Branch::where('name', $branchName)->first()   : null;
        $category = $categoryName ? \App\Models\AssetCategory::where('name', $categoryName)->first() : null;
        $subCategory = $subCategoryName ? \App\Models\AssetSubCategory::where('name', $subCategoryName)->first() : null;

        if (!$name || !$condition) {
            $errors[] = "Baris " . ($i + 1) . ": Nama dan Kondisi wajib diisi";
            continue;
        }

        Asset::create([
            'name'             => $name,
            'location'         => $location,
            'condition'        => $condition,
            'brand'            => $brand,
            'acquisition_year' => $year,
            'value'            => $value,
            'branch_id'        => $branch?->id,
            'category_id'      => $category?->id,
            'sub_category_id'  => $subCategory?->id,
        ]);

        $imported++;
    }

    // Hapus file temp
    \Illuminate\Support\Facades\Storage::disk('local')->delete($file);

    return response()->json([
        'message'  => "$imported aset berhasil diimport",
        'errors'   => $errors
    ]);
}
public function export()
{
    $assets = Asset::with(['branch', 'category', 'subCategory'])->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $headers = ['Nama Aset', 'Cabang', 'Kategori', 'Sub Kategori', 'Kondisi', 'Brand', 'Tahun', 'Nilai', 'Lokasi'];
    foreach ($headers as $i => $h) {
        $col = chr(65 + $i);
        $sheet->setCellValue($col . '1', $h);
        $sheet->getStyle($col . '1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
        ]);
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Data
    foreach ($assets as $i => $a) {
        $row = $i + 2;
        $sheet->setCellValue('A' . $row, $a->name);
        $sheet->setCellValue('B' . $row, $a->branch->name    ?? '-');
        $sheet->setCellValue('C' . $row, $a->category->name  ?? '-');
        $sheet->setCellValue('D' . $row, $a->subCategory->name ?? '-');
        $sheet->setCellValue('E' . $row, ucfirst($a->condition ?? '-'));
        $sheet->setCellValue('F' . $row, $a->brand            ?? '-');
        $sheet->setCellValue('G' . $row, $a->acquisition_year ?? '-');
        $sheet->setCellValue('H' . $row, $a->value ? 'Rp ' . number_format($a->value, 0, ',', '.') : '-');
        $sheet->setCellValue('I' . $row, $a->location         ?? '-');
    }

    $filename = 'Data_Aset_' . now()->format('Ymd_His') . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}
    
    public function show($id)
    {
        return response()->json(Asset::findOrFail($id));
    }

    

    public function destroy($id)
    {
        Asset::destroy($id);

        return response()->json([
            'message' => 'Asset berhasil dihapus'
        ]);
    }
}
