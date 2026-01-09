<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_product' => 'required',
            'price'        => 'required|numeric',
            'stock'        => 'required|integer|min:0',
            'category_id'  => 'required|exists:categories,id'
        ]);

        Product::create($request->all());

        return response()->json([
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }
}
