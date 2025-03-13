<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
        .order-detail-table th, .order-detail-table td {
            text-align: center;
            padding: 12px;
        }
        .order-detail-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .card-header {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }

        .btn-custom {
            background-color: #16a085;
            color: white;
        }
        .btn-custom:hover {
            background-color: #1abc9c;
        }

        /* Order Information Styling */
        .order-info-row {
            margin-bottom: 20px;
        }
        .order-info-title {
            font-weight: 600;
        }
        .order-info-value {
            font-weight: 500;
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
            <h2>Detail Order</h2>
            <div class="card border-0 shadow-lg">
                <div class="card-header">
                    <strong>Order Information</strong>
                </div>
                <div class="card-body">
                    <div class="row order-info-row">
                        <div class="col-md-6">
                            <p class="order-info-title">Order ID:</p>
                            <p class="order-info-value">{{ $order->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="order-info-title">Customer Name:</p>
                            <p class="order-info-value">{{ $order->user->name }}</p>
                        </div>
                    </div>

                    <div class="row order-info-row">
                        <div class="col-md-6">
                            <p class="order-info-title">Total Price:</p>
                            <p class="order-info-value">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="order-info-title">Order Status:</p>
                            <p class="order-info-value">{{ $order->order_status }}</p>
                        </div>
                    </div>

                    <div class="row order-info-row">
                        <div class="col-md-6">
                            <p class="order-info-title">Payment Method:</p>
                            <p class="order-info-value">{{ $order->payment_method }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="order-info-title">Payment Status:</p>
                            <p class="order-info-value">{{ $order->payment_status }}</p>
                        </div>
                    </div>

                    <div class="row order-info-row">
                        <div class="col-md-6">
                            <p class="order-info-title">Transaction ID:</p>
                            <p class="order-info-value">{{ $order->transaction_id }}</p>
                        </div>
                    </div>

                    <hr>

                    <h4>Order Details</h4>
                    <table class="table table-bordered order-detail-table">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($order->orderDetails as $detail)
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
                    </table>

                    <hr>
                    <h5><strong>Total Order Amount:</strong> Rp{{ number_format($total, 0, ',', '.') }}</h5>
                    <a href="{{ route('order.index') }}" class="btn btn-custom mt-3">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
