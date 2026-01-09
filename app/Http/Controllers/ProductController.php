<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(
            Product::with('category')
                ->orderBy('id', 'desc')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_product' => [
                'required',
                Rule::unique('products')
                    ->where('category_id', $request->category_id)
            ],
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ], [
            'name_product.unique' => 'Produk sudah ada di kategori ini'
        ]);

        Product::create([
            'name_product' => $request->name_product,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'category_id'  => $request->category_id
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_product' => [
                'required',
                Rule::unique('products')
                    ->where('category_id', $request->category_id)
                    ->ignore($id)
            ],
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ], [
            'name_product.unique' => 'Produk sudah ada di kategori ini'
        ]);

        Product::where('id', $id)->update([
            'name_product' => $request->name_product,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'category_id'  => $request->category_id
        ]);

        return response()->json([
            'message' => 'Produk berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
