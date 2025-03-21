<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Baru Ditambahkan</title>
</head>
<body>
    <h1>Halo, {{ $customerName }}!</h1>
    <p>Kami ingin memberitahukan bahwa produk baru telah ditambahkan ke toko kami.</p>
    <h3>Detail Produk:</h3>
    <ul>
        <li><strong>Nama Produk:</strong> {{ $productName }}</li>
        <li><strong>Kategori:</strong> {{ $productCategory }}</li>
    </ul>
    <p>Terima kasih telah berbelanja dengan kami!</p>
</body>
</html>
