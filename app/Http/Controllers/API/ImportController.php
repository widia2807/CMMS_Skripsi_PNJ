<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;


class ImportController extends Controller
{
   
public function importBranches(Request $request)
{
    try {

        if (!$request->hasFile('file')) {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 400);
        }

        $file = $request->file('file');

        // baca CSV
        $rows = array_map('str_getcsv', file($file));

        foreach ($rows as $index => $row) {

            if ($index == 0) continue; // skip header
            if (!isset($row[0])) continue;

            $type = strtolower(trim($row[1] ?? 'branch'));

            // mapping
            if (in_array($type, ['cabang', 'branch'])) {
                $type = 'branch';
            } elseif (in_array($type, ['ho', 'head office'])) {
                $type = 'ho';
            } else {
                $type = 'branch';
            }

            // hindari duplikat
            $exists = Branch::where('name', $row[0])->exists();
            if ($exists) continue;

            Branch::create([
                'name' => $row[0],
                'type' => $type,
                'company_id' => 1,
                'status' => 'active'
            ]);
        }

        return response()->json([
            'message' => 'Import success'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Import gagal',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
