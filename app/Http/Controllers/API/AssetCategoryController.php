<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetCategory;

class AssetCategoryController extends Controller
{
    public function index()
    {
        return response()->json(
            AssetCategory::select('id','name')->get()
        );
    }

    public function store(Request $request)
    {
        // VALIDASI
        if (!$request->name) {
            return response()->json([
                'message' => 'Nama category wajib diisi'
            ], 400);
        }

        $category = AssetCategory::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Category berhasil dibuat',
            'data' => $category
        ]);
    }
    }
