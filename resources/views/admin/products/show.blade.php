<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 12px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: block;
            border-radius: 5px;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #3d566e;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .product-image {
            width: 280px;
            height: 280px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-custom {
            border: none;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
        }
        .btn-back:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <hr>
        <a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="{{ route('products.index') }}" class="active"><i class="fa fa-box"></i> Products</a>
        <a href="{{ route('category.index') }}"><i class="fa fa-list"></i> Categories</a>
        <a href="{{ route('order.index') }}"><i class="fa fa-shopping-cart"></i> Orders</a>
        <a href="{{ route('customers.index') }}"><i class="fa fa-users"></i> Customers</a>
        <a href="{{ route('setting.index') }}"><i class="fa fa-gear"></i> Setting</a>        
        <hr>
        <form action="{{ route('auth.logout') }}" method="POST" class="d-grid p-2">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container mt-4">
            <h2 class="mb-4">Detail Produk</h2>
            <div class="card card-custom text-center">
                <div class="card-body">
                    <img src="{{ asset('storage/' . $product->image_url) }}" class="product-image" alt="Product Image">
                    <h3 class="mt-3 fw-bold">{{ $product->name }}</h3>
                    <p class="text-muted">{{ $product->description }}</p>
                    <h4 class="text-primary">Rp{{ number_format($product->price, 0, ',', '.') }}</h4>
                    <p class="fw-semibold"><strong>Stok:</strong> {{ $product->stock }}</p>
                    <a href="{{ route('products.index') }}" class="btn btn-back mt-3"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
