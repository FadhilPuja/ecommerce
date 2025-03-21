<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Baru dari {{ $userName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-lunas {
            color: green;
            font-weight: bold;
        }
        .status-belum-lunas {
            color: red;
            font-weight: bold;
        }
        .button-container {
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h3>Notifikasi Checkout Pesanan</h3>
    <p><strong>Nama Pelanggan:</strong> {{ $userName }}</p>
    <p><strong>Email:</strong> {{ $userEmail }}</p>
    <p><strong>ID Pesanan:</strong> #{{ $orderId }}</p>
    <p><strong>Total Harga:</strong> Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
    <p><strong>Total Produk:</strong> {{ $totalProducts }}</p>
    <p><strong>Total Kuantitas:</strong> {{ $totalQuantity }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $paymentMethod }}</p>
    <p><strong>Status Pembayaran:</strong> 
        <span class="{{ $paymentStatus == 'Paid' ? 'status-paid' : 'status-unpaid' }}">
            {{ $paymentStatus }}
        </span>
    </p>

    <h4>Daftar Produk:</h4>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($orderItems as $detail)
                @php
                    $productTotal = $detail->quantity * $detail->product->price;
                    $total += $productTotal;
                @endphp
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp{{ number_format($detail->product->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($productTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right;">Total Keseluruhan:</th>
                <th>Rp{{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <p>Silakan cek dashboard admin untuk detail lebih lanjut.</p>
</body>
</html>
