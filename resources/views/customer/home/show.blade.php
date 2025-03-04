<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Product Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Navbar */
        .navbar-brand {
            font-weight: bold;
            font-size: 1.4rem;
        }

        /* Product Image */
        .product-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Card Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Button Styling */
        .btn-custom {
            width: 100%;
            font-size: 1.1rem;
            padding: 10px;
            border-radius: 8px;
        }

        /* Price Styling */
        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">🛒 Mini E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home.index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
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

    <!-- Product Details -->
    <div class="container mt-5">
        <div class="row">
            @if ($product)
                <div class="col-md-6">
                    <img src="{{ asset($product->image_url) }}" class="product-image img-fluid" alt="{{ $product->name }}">
                </div>

                <!-- Product Info -->
                <div class="col-md-6">
                    <div class="card p-4">
                        <h2 class="fw-bold">{{ $product->name }}</h2>
                        {{-- <p class="text-muted">Category: <strong>{{ $product->category->name }}</strong></p> --}}
                        <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p>{{ $product->description }}</p>

                        {{-- <form action="{{ route('cart.add', $product->id) }}" method="POST"> --}}
                            @csrf
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1">
                            </div>
                            <button type="submit" class="btn btn-success btn-custom"><i class="fa fa-cart-plus"></i> Add to Cart</button>
                        </form>

                        <a href="{{ route('home.index') }}" class="btn btn-secondary btn-custom mt-3">⬅ Back to Home</a>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <p>Produk tidak ditemukan.</p>
                    <a href="{{ route('home.index') }}" class="btn btn-secondary btn-custom">⬅ Back to Home</a>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
