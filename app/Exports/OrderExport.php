<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil semua data order dengan relasi yang dibutuhkan.
    */
    public function collection()
    {
        return Order::with(['orderDetails.product', 'user'])->get();
    }

    /**
    * Definisi Header Kolom untuk File Excel.
    */
    public function headings(): array
    {
        return [
            'Customer Name', 'Total Price', 'Order Status',
            'Payment Method', 'Payment Status', 'Transaction ID',
            'Product Name', 'Quantity', 'Unit Price',
            'Total Price Per Product', 'Created At', 'Updated At'
        ];
    }

    /**
    */
    public function map($order): array
    {
        $rows = [];

        foreach ($order->orderDetails as $detail) {
            $productName = optional($detail->product)->name ?? 'Unknown';
            $quantity = $detail->quantity;
            $unitPrice = optional($detail->product)->price ?? 0;
            $productTotal = $quantity * $unitPrice;

            $rows[] = [
                optional($order->user)->name ?? 'Guest',
                number_format($order->total_price, 0, ',', '.'),
                ucfirst($order->order_status),
                ucfirst($order->payment_method),
                ucfirst($order->payment_status),
                $order->transaction_id ?: '-',
                $productName,
                $quantity,
                'Rp' . number_format($unitPrice, 0, ',', '.'),
                'Rp' . number_format($productTotal, 0, ',', '.'),
                optional($order->created_at)->format('d-m-Y H:i:s'),
                optional($order->updated_at)->format('d-m-Y H:i:s'),
            ];
        }

        return $rows;
    }
}
