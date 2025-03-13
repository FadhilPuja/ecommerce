<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart_Item;
use App\Models\MidtransHistory;
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
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function checkout(Request $request)
    {
        // Validasi request
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'payment_method' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $this->configureMidtrans(); // Konfigurasi Midtrans

            // Cek apakah ada pesanan yang belum dibayar
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

            // Ambil semua item dari cart berdasarkan user_id
            $cartItems = Cart_Item::where('user_id', $request->user_id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Keranjang Anda kosong.',
                ], 400);
            }

            // Hitung total harga
            $totalPrice = $cartItems->sum(fn($item) => $item->total_price);

            // Buat order baru
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_price' => $totalPrice,
                'order_status' => 'Pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'Unpaid',
                'transaction_id' => null,
            ]);

            // Menyimpan order detail
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->total_price,
                ]);
            }

            // Buat transaksi di Midtrans
            $snapToken = $this->createMidtransTransaction($cartItems, $order, $totalPrice);
            $order->update(['transaction_id' => $snapToken]);

            // Hapus cart setelah checkout
            Cart_Item::where('user_id', $request->user_id)->delete();

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

    private function createMidtransTransaction($cartItems, $order, $totalPrice)
    {
        $transaction = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $totalPrice,
            ],
            'item_details' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => $item->total_price / $item->quantity,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            })->toArray(),
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone_number,
                'address' => $order->user->address,
            ],
        ];

        return Snap::getSnapToken($transaction);
    }

    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info($payload);

        try {
            $orderId = $payload['order_id'];
            $statusCode = $payload['status_code'];
            $grossAmount = $payload['gross_amount'];
            $paymentType = $payload['payment_type'];
            $signatureKey = $payload['signature_key'];
            $serverKey = config('midtrans.server_key');

            $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($signature != $signatureKey) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $transactionStatus = $payload['transaction_status'];

            MidtransHistory::create([
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'payload' => json_encode($payload),
            ]);

            $order = Order::find($orderId);
            if (!$order) {
                return response()->json(['message' => 'Invalid order / Order not found!'], 404);
            }

            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $order->update([
                    'order_status' => 'Shipping',
                    'payment_status' => 'Paid',
                    'payment_method' => $paymentType,
                ]);

                foreach ($order->orderDetails as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->update(['quantity' => max(0, $product->quantity - $item->quantity)]);
                    }
                }
            } elseif (in_array($transactionStatus, ['expire', 'failure', 'cancel', 'deny', 'pending'])) {
                $order->update([
                    'order_status' => 'Cancelled',
                    'payment_status' => 'Unpaid',
                    'payment_method' => $paymentType,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}
