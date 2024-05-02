<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts(Request $request) {
        $products = Product::with('category')->get();
        return response()->json($products);
    }
}
