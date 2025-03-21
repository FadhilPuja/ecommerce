<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Pembayaran Diperbarui</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <h3>Notifikasi Pembayaran Pesanan Baru</h3>
    <p>Halo Admin,</p>

    <p>Status pembayaran untuk Pesanan #{{ $orderId }} telah diperbarui menjadi: <strong>{{ $transactionStatus }}</strong>.</p>
    <p>Total Pembayaran: Rp{{ $totalPrice }}</p>

    @if ($transactionStatus == 'settlement' || $transactionStatus == 'capture')
        <p>Pesanan ini telah berhasil dibayar dan sedang dalam proses pengiriman.</p>
    @elseif ($transactionStatus == 'expire' || $transactionStatus == 'failure' || $transactionStatus == 'cancel')
        <p>Pesanan ini telah dibatalkan karena pembayaran gagal atau kedaluwarsa.</p>
    @endif

    <p>Harap tindak lanjuti sesuai dengan status pembayaran ini.</p>
</body>
</html>
