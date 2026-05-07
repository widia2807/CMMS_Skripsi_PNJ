<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\ActivityLog;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BranchController extends Controller
{
    public function store(Request $request)
{
    $user = auth()->user();
     
    $request->validate([
        'name' => 'required'
    ]);

    $branch = Branch::create([
        'company_id' => $user->company_id,
        'name' => $request->name,
        'type' => $request->type,
        'status' => 'active'
    ]);

     ActivityLog::create([
        'description' => 'Berhasil menambahkan cabang ' . $branch->name
    ]);

    return response()->json([
        'message' => 'Cabang berhasil dibuat',
        'branch' => $branch
    ]);
}

public function importTemplate()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Nama', 'Tipe (branch/ho)'];
    foreach ($headers as $i => $h) {
        $col = chr(65 + $i);
        $sheet->setCellValue($col . '1', $h);
        $sheet->getStyle($col . '1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']],
        ]);
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Contoh
    $sheet->setCellValue('A2', 'Kantor Pusat');
    $sheet->setCellValue('B2', 'ho');
    $sheet->setCellValue('A3', 'Cabang Jakarta');
    $sheet->setCellValue('B3', 'branch');

    $writer = new Xlsx($spreadsheet);
    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, 'Template_Import_Cabang.xlsx', [
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
    $rows = $spreadsheet->getActiveSheet()->toArray();

    $imported = 0;
    foreach ($rows as $i => $row) {
        if ($i === 0 || empty($row[0])) continue;
        \App\Models\Branch::create([
            'name'   => $row[0],
            'type'   => in_array($row[1], ['ho', 'branch']) ? $row[1] : 'branch',
            'status' => 'active',
        ]);
        $imported++;
    }

    \Illuminate\Support\Facades\Storage::disk('local')->delete($file);

    return response()->json(['message' => "$imported cabang berhasil diimport"]);
}

public function active()
{
    $user = auth()->user();

    $branches = Branch::where('company_id', $user->company_id)
        ->where('status', 'active')
        ->get();

    return response()->json($branches);
}

public function index()
{
    $user = auth()->user();

    return response()->json(
        Branch::where('company_id', $user->company_id)->get()
    );
}

public function toggle($id)
{
    $branch = Branch::findOrFail($id);

    $branch->status = $branch->status === 'active'
        ? 'inactive'
        : 'active';

    $branch->save();

    // 🔥 SYNC USER
    User::where('branch_id', $branch->id)
        ->update([
            'status' => $branch->status === 'active'
                ? 'active'
                : 'inactive'
        ]);

    return response()->json(['message' => 'updated']);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'type' => 'required|in:branch,ho'
    ]);

    $branch = Branch::findOrFail($id);

    $branch->update([
        'name' => $request->name,
        'type' => $request->type
    ]);

    return response()->json(['message' => 'updated']);
}

public function destroy($id)
{
    Branch::findOrFail($id)->delete();

    return response()->json(['message' => 'deleted']);
}
}
