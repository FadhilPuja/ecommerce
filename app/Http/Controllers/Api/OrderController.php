<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    private function configureMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.id_3ds');
    }

    public function checkout(Request $request)
    {
        // Validasi request
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'cart_items' => ['required', 'array'],
            'payment_method' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $this->configureMidtrans(); // Konfigurasi Midtrans

            // Cek apakah user sudah memiliki pesanan yang belum dibayar
            $existingOrder = Order::where('user_id', $request->user_id)
                ->where('payment_status', 'Unpaid')
                ->first();

            if ($existingOrder) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah memiliki pesanan yang belum dibayar.',
                    'order_id' => $existingOrder->id,
                    'snap_token' => $existingOrder->transaction_id,
                ], 400);
            }

            // Hitung total harga
            $totalPrice = collect($request->cart_items)->sum(function ($item) {
                return Product::findOrFail($item['product_id'])->price * $item['quantity'];
            });

            // Buat order baru
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_price' => $totalPrice,
                'order_status' => 'Pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'Unpaid',
                'transaction_id' => null,
            ]);

            // Buat detail order
            $this->createOrderDetails($request->cart_items, $order->id);

            // Buat transaksi ke Midtrans
            $snapToken = $this->createMidtransTransaction($request, $order, $totalPrice);

            // Update order dengan snap token dari Midtrans
            $order->update(['transaction_id' => $snapToken]);

            DB::commit();
            return response()->json([                
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.',
                'total_bayar' => $totalPrice,
                'order_id' => $order->id,
                'snap_token' => $snapToken,
                'url_website' => "https://app.sandbox.midtrans.com/snap/v4/redirection/" . $snapToken
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat pesanan.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function midtransCallback(Request $request)
    {
        // Validasi tanda tangan Midtrans
        Log::info('Midtrans Callback Data:', $request->all());
        if (!$this->validateMidtransSignature($request)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Ambil order berdasarkan transaction_id
        $order = Order::where('transaction_id', $request->order_id)->firstOrFail();

        // Update status pembayaran berdasarkan status transaksi Midtrans
        $order->update([
            'payment_status' => $request->transaction_status == 'settlement' ? 'Paid' : 'Unpaid',
            'order_status' => $request->transaction_status == 'settlement' ? 'Shipping' : 'Cancelled'
        ]);

        return response()->json(['message' => 'Success']);
    }

    private function createOrderDetails($cartItems, $orderId)
    {
        foreach ($cartItems as $item) {
            $product = Product::findOrFail($item['product_id']);
            OrderDetail::create([
                'order_id' => $orderId,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'subtotal' => $product->price * $item['quantity'],
            ]);
        }
    }

    private function createMidtransTransaction($request, $order, $totalPrice)
    {
        $transaction = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id,
                'gross_amount' => $totalPrice,
            ],
            'item_details' => collect($request->cart_items)->map(function ($item) {
                $product = Product::findOrFail($item['product_id']);
                return [
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'name' => $product->name,
                ];
            })->toArray(),
            'customer_details' => [
                'first_name' => User::findOrFail($request->user_id)->name,
                'email' => User::findOrFail($request->user_id)->email,
                'phone' => User::findOrFail($request->user_id)->phone_number,
                'address' =>User::findOrFail($request->user_id)->address,
            ],
        ];

        return Snap::getSnapToken($transaction);
    }

    private function validateMidtransSignature($request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        return $hashed === $request->signature_key;
    }
}
