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
        return response()->json(Category::all());
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $category = Category::create($request->only('name', 'company_id'));
        return response()->json($category, 201);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->only('name'));
        return response()->json($category);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}