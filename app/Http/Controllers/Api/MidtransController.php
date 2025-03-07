<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\MidtransHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $payload = $request->all();

        // Logging payload untuk debugging
        Log::info('Midtrans Callback:', $payload);

        try {
            // Validasi data dari Midtrans
            $orderId = $payload['order_id'];
            $statusCode = $payload['status_code'];
            $grossAmount = $payload['gross_amount'];
            $signatureKey = $payload['signature_key'];
            $serverKey = config('midtrans.server_key');

            // Validasi signature untuk keamanan
            $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($signature !== $signatureKey) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Ambil status transaksi dari Midtrans
            $transactionStatus = $payload['transaction_status'];

            // Simpan riwayat transaksi Midtrans
            MidtransHistory::create([
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'payload' => json_encode($payload)
            ]);

            // Cari order berdasarkan ID
            $order = Order::find($orderId);
            if (!$order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }

            // Update status order berdasarkan transaksi Midtrans
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $order->order_status = 'Shipping'; // Order akan dikirim setelah pembayaran sukses
                $order->payment_status = 'Paid';

                // Kurangi stok produk
                foreach ($order->orderDetails as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->update([
                            'quantity' => $product->quantity - $item->quantity
                        ]);
                    }
                }
            } elseif (in_array($transactionStatus, ['expire', 'failure', 'cancel', 'deny'])) {
                $order->order_status = 'Cancelled';
                $order->payment_status = 'Unpaid';
            }

            // Simpan perubahan order
            $order->payment_method = $payload['payment_type'];
            $order->save();

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
