<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #35495e;
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
            transition: background-color 0.3s;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #4a6572;
        }
        .content {
            margin-left: 270px;
            padding: 30px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-custom {
            background-color: #5d6d7e;
            color: white;
        }
        .btn-custom:hover {
            background-color: #85929e;
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
        <a href="#"><i class="fa fa-shopping-cart"></i> Orders</a>
        <a href="#"><i class="fa fa-users"></i> Customers</a>
        <a href="#"><i class="fa fa-chart-line"></i> Report</a>
        <a href="#"><i class="fa fa-cogs"></i> Setting</a>
        <hr>
        <form action="{{ route('auth.logout') }}" method="POST" class="d-grid p-2">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container">
            <div class="card p-4">
                <h2 class="mb-4">Edit Produk</h2>
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Gambar Produk</label>
                        <input type="file" id="image_url" name="image_url" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                        <br>
                        <img src="{{ asset('storage/' . $product->image_url) }}" width="100" class="mt-2 rounded" alt="Current Image">
                    </div>
                    
                    <button type="submit" class="btn btn-custom">Update Produk</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>