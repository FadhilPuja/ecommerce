<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Customer</title>
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

        /* Cart Table Styling */
        .cart-table th, .cart-table td {
            vertical-align: middle;
        }

        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('home.index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('cart.index') }}">Cart</a></li>
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

    <!-- Cart Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-3">Your Shopping Cart</h2>
        <p class="text-center text-muted">Review the items in your cart before proceeding to checkout.</p>

        <!-- Cart Items Table -->
        <div class="table-responsive">
            <table class="table table-striped cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $cartItem)
                        <tr>
                            <td>
                                <img src="{{ asset($cartItem->product->image_url) }}" class="product-image" alt="{{ $cartItem->product->name }}">
                                <span>{{ $cartItem->product->name }}</span>
                            </td>
                            <td>Rp {{ number_format($cartItem->product->price, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.update', $cartItem->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" class="form-control w-50 d-inline">
                                    <button type="submit" class="btn btn-warning btn-sm ms-2">Update</button>
                                </form>
                            </td>
                            <td>Rp {{ number_format($cartItem->product->price * $cartItem->quantity, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Cart Total -->
        @if ($cartItems->isNotEmpty())
            <div class="d-flex justify-content-end">
                <h4 class="cart-total">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('home.index') }}" class="btn btn-secondary w-100">⬅ Back to Shop</a>
                <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">Proceed to Checkout</a>
            </div>
        @else
            <p class="text-center text-muted">Your cart is empty.</p>
            <a href="{{ route('home.index') }}" class="btn btn-primary w-100">Go to Shop</a>
        @endif
    </div>

</body>
</html>
