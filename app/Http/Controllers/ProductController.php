<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Mail\ProductNotification;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate();
        return view('admin.products.index', compact('products'));
    }

    public function export()
    {
        return Excel::download(new ProductExport, 'product.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        return back()->with('success', 'Produk berhasil diimport!');
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

        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'category_id' => $validatedData['category_id'],
            'image_url' => $validatedData['image_url'],
        ]);       
        
        $customers = User::where('role', 'customer')->get();        

        foreach ($customers as $index => $customer) {
            Notification::create([
                'user_id' => $customer->id,
                'type' => 'New Product',
                'message' => "Produk {$product->name} dengan kategori {$product->category->name} baru saja ditambahkan",
                'is_read' => false,
            ]);
        
            Mail::to($customer->email)->send(new ProductNotification($product, $customer->name));
        
            if ($index < count($customers) - 1) {
                sleep(1);
            }
        }        

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
