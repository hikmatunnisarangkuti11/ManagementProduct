<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_category' => 'required|unique:categories,name_category'
        ], [
            'name_category.unique' => 'Kategori sudah ada'
        ]);

        Category::create([
            'name_category' => $request->name_category
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_category' => [
                'required',
                Rule::unique('categories', 'name_category')->ignore($id)
            ]
        ], [
            'name_category.unique' => 'Kategori sudah ada'
        ]);

        Category::where('id', $id)->update([
            'name_category' => $request->name_category
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Kategori masih digunakan oleh produk'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
