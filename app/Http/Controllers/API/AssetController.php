<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

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

public function export()
{
    $assets = Asset::all();

    $output = "Nama Asset\tLokasi\tBrand\tNilai\tTahun\n";

    foreach ($assets as $a) {
        $output .= "{$a->name}\t{$a->location}\t{$a->brand}\t{$a->value}\t{$a->acquisition_year}\n";
    }

    return response($output)
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment; filename="assets.xls"');
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
