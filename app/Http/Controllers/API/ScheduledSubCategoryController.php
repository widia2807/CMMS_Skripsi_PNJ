<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScheduledSubCategory;
use Illuminate\Http\Request;

class ScheduledSubCategoryController extends Controller
{
    // GET /api/scheduled-sub-categories?category_id=X
    public function index(Request $request)
    {
        $query = ScheduledSubCategory::where('is_active', true);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->orderBy('name')->get());
    }

    // POST /api/scheduled-sub-categories
    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name'        => 'required|string|max:100',
        'description' => 'nullable|string',
    ]);

    $sub = ScheduledSubCategory::create([
        'category_id' => $request->category_id,
        'name'        => $request->name,
        'description' => $request->description,
    ]);

    return response()->json($sub, 201);
}

    // PUT /api/scheduled-sub-categories/{id}
    public function update(Request $request, $id)
    {
        $sub = ScheduledSubCategory::findOrFail($id);

        $request->validate([
            'name'        => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
        ]);

        $sub->update($request->only([
            'name', 'description', 'is_active'
        ]));

        return response()->json($sub);
    }

    // DELETE /api/scheduled-sub-categories/{id}
    public function destroy($id)
    {
        $sub = ScheduledSubCategory::findOrFail($id);
        $sub->update(['is_active' => false]); // soft disable, bukan hapus
        return response()->json(['message' => 'Sub kategori dinonaktifkan.']);
    }
}