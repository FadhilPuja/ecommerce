<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Customer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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
        .customer-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
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
    <div class="content">
        <div class="container mt-4">
            <h2>Daftar Customer</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table id="customerTable" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $customer->profile_image) }}" class="customer-image" alt="Customer Image">
                        </td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ $customer->role }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($customers->isEmpty())
                <p class="text-center">Belum ada customer.</p>
            @endif
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#customerTable').DataTable();
        });
    </script>
</body>
</html>