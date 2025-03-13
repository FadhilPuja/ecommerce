<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{    
    public function index()
    {        
        $products = Product::paginate(10);        
        
        return response()->json([
            'data' => $products
        ], );
    }

    public function filter(Request $request)
    {
        $categoryId = $request->query('category_id');

        if (!$categoryId) {
            return response()->json([
                'error' => 'Parameter category_id wajib diisi'
            ], 400);
        }

        $products = Product::where('category_id', $categoryId)->paginate(10);

        return response()->json([
            'data' => $products
        ]);
    }

}
