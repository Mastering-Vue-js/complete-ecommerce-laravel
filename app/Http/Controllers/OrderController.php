<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addOrder(Request $request) {

        $total = 0;

        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->status = 'pending';
        $order->payment_method = $request->payment_method;
        $order->payment_status = 'pending';
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->line1 = $request->line1;
        $order->line2 = $request->line2;
        $order->city = $request->city;
        $order->country = $request->country;
        $order->notes = $request->notes;
        $order->save();

        $cart = Cart::where('user_id', $request->user()->id)->get();
        foreach($cart as $item){
            $OrderProduct = new OrderProduct();
            $OrderProduct->order_id = $order->id;
            $OrderProduct->product_id = $item->product_id;
            $OrderProduct->quantity = $item->quantity;
            $OrderProduct->price = $item->price;
            $OrderProduct->save();

            $total += $item->quantity*$item->price;
        }

        Cart::where('user_id', $request->user()->id)->delete();

        $order->total = $total;

        return $this->success('Add order', $order);
    }
}
