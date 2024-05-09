<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller {
    public function getCart(Request $request) {
        $cart = Cart::where('user_id', $request->user()->id)->get();
        return $this->success('Get cart', $cart);  
    }

    public function addToCart(Request $request) {

        $cart = new Cart();
        $cart->user_id = $request->user()->id;
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;
        $cart->price = $request->price;
        $cart->total = $request->quantity * $request->price;
        $cart->save();

        return $this->success('Add to cart', $cart);
    }

    public function updateCart(Request $request) {

        $cart = Cart::where('user_id', $request->user()->id)->where('id', $request->cart_id)->first();
        $cart->quantity = $request->quantity;
        $cart->total = $request->quantity * $cart->price;
        $cart->save();

        return $this->success('Update cart', $cart);
    }

    public function deleteCart(Request $request, $id) {
        $cart = Cart::where('user_id', $request->user()->id)->where('id', $id)->first();
        $cart->delete();

        return $this->success('Delete cart', $cart);
    }
}
