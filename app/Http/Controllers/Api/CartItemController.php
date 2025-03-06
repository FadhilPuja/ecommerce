<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user_id = auth()->id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);

        $product = Product::findOrFail($request->product_id);
        $total_price = $product->price * $request->quantity;

        $cartItem = Cart_Item::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'user_id' => $user_id,
                'product_id' => $product->id,
            ],
            [
                'quantity' => $request->quantity,
                'total_price' => $total_price,
            ]
        );

        return response()->json(['message' => 'Product added to cart', 'item' => $cartItem]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $cartItem = Cart_Item::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found']);
        }

        $product = Product::findOrFail($cartItem->product_id);
        $cartItem->quantity = $request->quantity;
        $cartItem->total_price = $product->price * $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart Updated Successfully', 'item' => $cartItem]);
    }

    public function removeCartItem($id)
    {
        $cartItem = Cart_Item::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found']);
        }

        $cartItem->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }
}
