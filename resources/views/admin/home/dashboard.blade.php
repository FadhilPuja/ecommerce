<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
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
        .profile-image {
            display: block;
            margin: 10px auto;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 2px solid white;
        }
        .card:hover {
            transform: scale(1.05);
            transition: 0.3s ease-in-out;
        }
        .notification-icon {
            position: relative;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            font-size: 12px;
            padding: 4px 7px;
            border-radius: 50%;
            display: none;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <div class="text-center">
            @if(Auth::user()->image_url)
                <img src="{{ asset('storage/' . Auth::user()->image_url) }}" alt="Profile Image" class="profile-image">
            @else
                <img src="https://via.placeholder.com/80" class="profile-image" alt="No Profile Image">
            @endif
            <p class="text-center"><strong>{{ Auth::user()->name }}</strong></p>
        </div>
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
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container mt-4 d-flex justify-content-between align-items-center">
            <h2>Dashboard</h2>

            <!-- Bell Icon dengan Counter Notifikasi -->
            <div class="notification-icon">
                <a href="{{ route('notification.index') }}" class="text-dark">
                    <i class="fa fa-bell fa-2x"></i>
                    <span id="notificationCount" class="notification-badge">0</span>
                </a>
            </div>
        </div>

        <p>Welcome, <strong>{{ Auth::user()->name }}</strong></p>

        <div class="row mt-4">
            <!-- Total Products -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fa fa-box"></i> Total Products</h5>
                        <h2>{{ $totalProducts }}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3 shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fa fa-shopping-cart"></i> Total Orders</h5>
                        <h2>{{ $totalOrders }}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3 shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fa fa-users"></i> Total Customers</h5>
                        <h2>{{ $totalCustomers }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary"><i class="fa fa-box"></i> Manage Products</a>
            <a href="{{ route('order.index') }}" class="btn btn-success"><i class="fa fa-shopping-cart"></i> Manage Orders</a>
            <a href="{{ route('customers.index') }}" class="btn btn-warning"><i class="fa fa-users"></i> Manage Customers</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchNotifications() {
            fetch('/admin/notification/unread-count')
                .then(response => response.json())
                .then(data => {
                    let count = data.count;
                    let badge = document.getElementById('notificationCount');
                    if (count > 0) {
                        badge.innerText = count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        setInterval(fetchNotifications, 5000);
        fetchNotifications();
    </script>

</body>
</html>
