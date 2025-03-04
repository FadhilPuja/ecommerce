<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 12px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <hr>
        <a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="{{ route('products.index') }}"><i class="fa fa-box"></i> Products</a>
        <a href="{{ route('category.index') }}"><i class="fa fa-list"></i> Categories</a>
        <a href=""><i class="fa fa-shopping-cart"></i> Orders</a>
        <a href=""><i class="fa fa-users"></i> Customers</a>
        <a href="#"><i class="fa fa-chart-line"></i> Report</a>
        <a href="#"><i class="fa fa-gear"></i> Setting</a>
        <hr>
        <form action="{{ route('auth.logout') }}" method="POST" class="d-grid p-2">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container mt-4">
            <h2>Edit Produk</h2>
            
            <form action="{{ route('products.update', $products->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $products->name) }}" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $products->description) }}</textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="price">Harga</label>
                    <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $products->price) }}" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="stock">Stok</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', $products->stock) }}" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="image_url">Gambar Produk</label>
                    <input type="file" id="image_url" name="image_url" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                    <br>
                    <img src="{{ asset('storage/' . $products->image_url) }}" width="100" class="mt-2" alt="Current Image">
                </div>
                
                <button type="submit" class="btn btn-primary">Update Produk</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>