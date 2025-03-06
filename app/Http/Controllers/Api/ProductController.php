<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{    
    public function index()
    {        
        $products = Product::paginate();        
        
        return response()->json([
            'data' => $products
        ], );
    }
}
