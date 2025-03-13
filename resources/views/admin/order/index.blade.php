<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .sidebar .active {
            background-color: #1abc9c;
        }

        /* Content Section */
        .content {
            margin-left: 250px;
            padding: 30px;
        }
        .content h2 {
            font-size: 32px;
            font-weight: 700;
        }

        /* Table Styles */
        table.dataTable thead th {
            background-color: #2980b9;
            color: white;
        }
        table.dataTable tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge {
            font-size: 0.9em;
        }

        .btn-custom {
            background-color: #16a085;
            color: white;
        }
        .btn-custom:hover {
            background-color: #1abc9c;
        }

        /* Pagination Styling */
        .pagination .page-item .page-link {
            border: none;
            background-color: #1abc9c;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white">Admin Panel</h4>
        <hr class="border-light">
        <a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="{{ route('products.index') }}"><i class="fa fa-box"></i> Products</a>
        <a href="{{ route('category.index') }}"><i class="fa fa-list"></i> Categories</a>
        <a href="{{ route('order.index') }}" class="active"><i class="fa fa-shopping-cart"></i> Orders</a>
        <a href="{{ route('customers.index') }}"><i class="fa fa-users"></i> Customers</a>
        <a href="{{ route('setting.index') }}"><i class="fa fa-cogs"></i> Settings</a>
        <hr class="border-light">
        <form action="{{ route('auth.logout') }}" method="POST" class="d-grid p-2">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container">
            <h2>Order List</h2>
            <table id="orderTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer Name</th>
                        <th>Total Price</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status == 'Unpaid' ? 'danger' : 'success' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' : ($order->order_status == 'canceled' ? 'danger' : 'success') }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
