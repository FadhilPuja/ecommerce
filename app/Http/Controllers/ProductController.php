<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)    
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer'],
            'category_id' => ['required', 'exists:categories,id'], 
            'image_url' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $validatedData['image_url'] = $request->file('image_url')->store('images', 'public');

        Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'category_id' => $validatedData['category_id'],
            'image_url' => $validatedData['image_url'],
        ]);

        return to_route('products.index')->with('success', 'Product created successfully');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer'],
            'category_id' => ['required', 'exists:categories,id'], // Pastikan dikirim
            'image_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image_url')) {
            Storage::delete('public/' . $product->image_url);
            $validatedData['image_url'] = $request->file('image_url')->store('images', 'public');
        } else {
            unset($validatedData['image_url']); // Jika tidak ada gambar baru, jangan ubah yang lama
        }

        $product->update($validatedData);

        return to_route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        Storage::delete('public/' . $product->image_url);

        $product->delete();

        return back()->with('success', 'Product deleted successfully');
    }
}
