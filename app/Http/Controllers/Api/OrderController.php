<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'cart_items' => ['required', 'array'],
            'payment_method' => ['required', 'string'],
        ]);
        
        
        try {
            DB::beginTransaction();
            
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.id_3ds');

            $totalPrice = 0;
            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $totalPrice += $product->price * $item['quantity'];
            }

            $order = Order::create([
                'user_id' => $request->user_id,
                'total_price' => $totalPrice,
                'order_status' => 'Pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'Unpaid',
                'transaction_id' => null,
            ]);

            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ]);
            }

            $transactionDetails = [
                'order_id' => 'ORDER-' . $order->id,
                'gross_amount' => $totalPrice,
            ];

            $itemDetails = [];
            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemDetails[] = [
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'name' =>$product->name,
                ];
            }

            $user = User::findOrFail($request->user_id);
            $customerDetail = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number
            ];

            $transaction = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetail,
            ];

            $snapToken = Snap::createTransaction($transaction);
            dd($snapToken);
            $order->update([
                'transaction_id' => $snapToken,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.',
                'order_id' => $order->id,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed != $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('transaction_id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($request->transaction_status == 'settlement') {
            $order->update(['payment_status' => 'Paid', 'order_status' => 'Shipping']);
        } elseif ($request->transaction_status == 'cancel') {
            $order->update(['payment_status' => 'Unpaid', 'order_status' => 'Cancelled']);
        }

        return response()->json(['message' => 'Success']);
    }
}
