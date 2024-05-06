<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts(Request $request) {
        $products = Product::with('category')->get();
        return $this->success('Products retrieved successfully', $products);
    }

    public function addProduct (Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ]);

        // handle image upload
        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images'), $image_name);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->image = $image_name;
        $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->save();

        return $this->success('Product added successfully', $product);
    }

    public function deleteProduct (Request $request, $id) {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('Product not found', 404);
        }
        $product->delete();
        return $this->success('Product deleted successfully');
    }

    public function updateProduct (Request $request) {
        $this->validate($request, [
            'product_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ]);

        $product = Product::find($request->product_id);
        if (!$product) {
            return $this->error('Product not found', 404);
        }

        // delete existing image
        if ($product->image) {
            unlink(public_path('images/' . $product->image));
        }

        // handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('images'), $image_name);
            $product->image = $image_name;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->save();

        return $this->success('Product updated successfully', $product);
    }

}
