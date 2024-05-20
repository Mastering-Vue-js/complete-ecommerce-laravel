<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\Coupon;

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
        $order->coupon = $request->coupon;
        $order->notes = $request->notes;
        $order->save();

        $cart = Cart::where('user_id', $request->user()->id)->get();

        foreach($cart as $item) {
            $OrderProduct = new OrderProduct();
            $OrderProduct->order_id = $order->id;
            $OrderProduct->product_id = $item->product_id;
            $OrderProduct->quantity = $item->quantity;
            $OrderProduct->price = $item->price;
            $OrderProduct->save();

            $total += $item->quantity*$item->price;
        }

        Cart::where('user_id', $request->user()->id)->delete();

        $order->total = $this->calculateTotals($request->coupon, $total);
        $order->save();

        return $this->success('Add order', $order);
    }

    public function calculateTotals($coupon, $total) {
        if($coupon){
            $coupon = Coupon::where('code', $coupon)->first();
            if($coupon){
                if($coupon->type == 'fixed'){
                    $total = $total - $coupon->discount;
                } else {
                    $total = $total - ($total * $coupon->discount / 100);
                }
            }
        }
        return $total;
    }

    public function getOrder(Request $request) {
        $orders = Order::with('products')->where('user_id', $request->user()->id)->get();
        // foreach($orders as $order){
        //     $order->products;
        // }
        return $this->success('Get orders', $orders);
    }

    public function getAdminOrders(Request $request) {
        $orders = Order::with('products')->with('user')->get();
        return $this->success('Get orders', $orders);
    }

    public function getAdminOrder(Request $request, $id) {
        $order = Order::with('products')->with('user')->where('id', $id)->first();
        return $this->success('Get single order', $order);
    }

    public function updateAdminOrder(Request $request) {
        $order = Order::find($request->id);
        $order->status = $request->status;
        $order->notes = $request->notes;
        $order->save();

        $products = $request->products;

        foreach($products as $product){
            $OrderProduct = OrderProduct::where('order_id', $order->id)->where('product_id', $product['id'])->first();
            $OrderProduct->quantity = $product['pivot']['quantity'];
            $OrderProduct->save();
        }

        return $this->success('Update order', $order);
    }
}
