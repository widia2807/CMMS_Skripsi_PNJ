<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetSubCategory;

class AssetSubCategoryController extends Controller
{
    public function index(Request $request)
{
    
    $categoryId = $request->query('category_id');

    if (!$categoryId) {
        return response()->json([]);
    }

    return response()->json(
        AssetSubCategory::where('asset_category_id', $categoryId)
            ->select('id', 'name')
            ->get()
    );
}

    public function store(Request $request)
{
    return AssetSubCategory::create([
        'name' => $request->name,
        'asset_category_id' => $request->asset_category_id
    ]);
}
}
