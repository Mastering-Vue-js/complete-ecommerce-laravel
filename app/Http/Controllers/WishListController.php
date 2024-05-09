<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use Illuminate\Http\Request;

class WishListController extends Controller {
    public function getWishList(Request $request) {
        $wishlist = WishList::where('user_id', $request->user()->id)->get();
        return $this->success('Get wishlist', $wishlist);
    }

    public function addToWishList(Request $request) {

        $wishlist = new WishList();
        $wishlist->user_id = $request->user()->id;
        $wishlist->product_id = $request->product_id;
        $wishlist->save();

        return $this->success('Add to wishlist', $wishlist);
    }

    public function deleteWishList(Request $request, $id) {
        $wishlist = WishList::where('user_id', $request->user()->id)->where('id', $id)->first();
        $wishlist->delete();

        return $this->success('Delete wishlist', $wishlist);
    }
}
