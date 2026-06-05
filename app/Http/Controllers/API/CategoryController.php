<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        $category = Category::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        return response()->json($category);
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cegah duplikat nama dalam company yang sama
        $exists = Category::where('company_id', auth()->user()->company_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Kategori dengan nama ini sudah ada.'], 422);
        }

        $category = Category::create([
            'name'       => $request->name,
            'company_id' => auth()->user()->company_id,
        ]);

        return response()->json($category, 201);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, $id)
    {
        $category = Category::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cegah duplikat nama (kecuali dirinya sendiri)
        $exists = Category::where('company_id', auth()->user()->company_id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Kategori dengan nama ini sudah ada.'], 422);
        }

        $category->update(['name' => $request->name]);

        return response()->json($category);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $category = Category::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        // Cegah hapus jika masih dipakai
        $usedInSchedule = \App\Models\ScheduledMaintenance::where('category_id', $id)->exists();
        $usedInRepair   = \App\Models\RepairRequest::where('category_id', $id)->exists();

        if ($usedInSchedule || $usedInRepair) {
            return response()->json([
                'message' => 'Kategori tidak bisa dihapus karena masih digunakan.'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}