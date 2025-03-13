<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
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
            <h2>Pengaturan Akun</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="profile_image" class="form-label">Photo Profile</label>
                    <input type="file" name="profile_image" class="form-control">
                    @if($user->image_url)
                        <img src="{{ asset('storage/' . $user->image_url) }}" class="img-thumbnail mt-2" width="100">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ $user->phone_number }}">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" class="form-control">{{ $user->address }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password (Optional)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
