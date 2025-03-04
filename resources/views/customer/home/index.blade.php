<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Customer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Navbar */
        .navbar-brand {
            font-weight: bold;
            font-size: 1.4rem;
        }

        /* Product Image Styling */
        .product-image {
            display: block;
            margin: 10px auto;
            border-radius: 10px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        /* Card Styling */
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }

        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Category Button Styling */
        .category-button {
            background-color: #f8f9fa;
            color: #212529;
            border: 2px solid #007bff;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .category-button:hover {
            background-color: #007bff;
            color: white;
        }

        /* Search Bar */
        .search-bar {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand" href="#">🛒 Mini E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
                    <li class="nav-item">
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm ms-2">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-3">Welcome, <strong>{{ auth()->user()->name }}</strong>! 👋</h2>
        <p class="text-center text-muted">Explore and shop your favorite products.</p>

        <!-- Search Bar -->
        <div class="row justify-content-center search-bar">
            <div class="col-md-6">
                <form action="{{ route('home.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search for products..." value="{{ request()->query('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories as Buttons -->
        <div class="text-center my-4">
            @foreach ($categories as $category)
                <a href="{{ route('home.index', ['category' => $category->id]) }}" class="category-button">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Products Grid -->
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="{{ asset($product->image_url) }}" class="product-image" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <a href="{{ route('home.show', $product->id) }}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($products->isEmpty())
            <p class="text-center text-muted">No products available at the moment.</p>
        @endif
    </div>

</body>
</html>
