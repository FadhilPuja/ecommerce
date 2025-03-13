<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Sidebar Styling */
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
        /* Table Styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .btn-sm {
            padding: 6px 10px;
            font-size: 14px;
        }
        .alert {
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <hr>
        <a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="{{ route('products.index') }}"><i class="fa fa-box"></i> Products</a>
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
            <h2 class="mb-3">Manajemen Kategori</h2>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Button Tambah -->
            <a href="{{ route('category.create') }}" class="btn btn-success mb-3">
                <i class="fa fa-plus"></i> Tambah Kategori
            </a>

            <!-- Tabel Kategori -->
            <div class="card shadow-sm p-3">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>                                
                                <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($categories->isEmpty())
                    <p class="text-center text-muted">Belum ada kategori.</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fade out alert success
        setTimeout(() => {
            let alert = document.querySelector(".alert");
            if (alert) {
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);

        // Konfirmasi sebelum menghapus kategori
        document.querySelectorAll(".delete-form").forEach(form => {
            form.addEventListener("submit", function(event) {
                if (!confirm("Apakah Anda yakin ingin menghapus kategori ini?")) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
