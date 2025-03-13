<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #6c757d;
            box-shadow: 0 0 5px rgba(108, 117, 125, 0.5);
        }
        .btn-custom {
            background-color: #6c757d;
            color: white;
            border-radius: 8px;
            padding: 10px;
        }
        .btn-custom:hover {
            background-color: #5a6268;
        }
        .image-preview {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 8px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <div class="card-header text-center bg-white border-0">
                        <h4 class="fw-bold">Add Product</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>                                
                            </div>

                            <div class="mb-3">
                                <label for="image_url" class="form-label">Product Image</label>
                                <input type="file" id="image_url" name="image_url" class="form-control" accept="image/*" required onchange="previewImage(event)">
                                <img id="image-preview" class="image-preview mt-3" alt="Product Preview">
                            </div>

                            <button type="submit" class="btn btn-custom w-100">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>