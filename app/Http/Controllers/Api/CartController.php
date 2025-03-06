<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{    
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty']);
        }

        $cartItem = Cart_Item::where('cart_id', $cart->id)->with('product')->get();

        return response()->json(['cart' => $cart, 'items' => $cartItem]);
    }

    public function clearCart ()
    {
        $user_id = auth()->id();

        $cart = Cart::where('user_id', $user_id)->first();

        if ($cart) {
            Cart_Item::where('cart_id', $cart->id)->delete();
        }

        return response()->json(['message' => 'Cart cleared successfully']);
    }
}
