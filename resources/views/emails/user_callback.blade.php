<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pembayaran Pesanan Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <h3>Notifikasi Pembayaran Pesanan</h3>
    <p>Halo {{ $userName }},</p>

    <p>Status pembayaran untuk Pesanan #{{ $orderId }} Anda telah diperbarui menjadi: <strong>{{ $transactionStatus }}</strong>.</p>
    <p>Total Pembayaran: Rp{{ $totalPrice }}</p>

    @if ($transactionStatus == 'settlement' || $transactionStatus == 'capture')
        <p>Pesanan Anda telah berhasil dibayar dan sedang dalam proses pengiriman.</p>
    @elseif ($transactionStatus == 'expire' || $transactionStatus == 'failure' || $transactionStatus == 'cancel')
        <p>Pesanan Anda telah dibatalkan karena pembayaran gagal atau kedaluwarsa.</p>
    @endif

    <p>Terima kasih telah berbelanja di toko kami!</p>
</body>
</html>
