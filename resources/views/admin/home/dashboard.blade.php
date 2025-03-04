<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Sidebar Styling */
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
            margin-left: 260px; /* Hindari tertutup sidebar */
            padding: 20px;
        }
        .profile-image {
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
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
        <form action="{{ route('auth.logout') }}" method="POST" class="d-grid">
            @csrf
            <button type="submit" class="btn btn-danger"><i class="fa fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">
                            <h4>Welcome to Dashboard</h4>
                        </div>
                        <div class="card-body text-center">
                            <p>Hello, <strong>{{ Auth::user()->name }}</strong></p>
                            <p>Your email: <strong>{{ Auth::user()->email }}</strong></p>

                            <p><strong>Profile Image:</strong></p>
                            @if(Auth::user()->image_url)
                                <img src="{{ asset('storage/' . Auth::user()->image_url) }}" alt="Profile Image" class="profile-image">
                            @else
                                <p>No profile image uploaded.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
