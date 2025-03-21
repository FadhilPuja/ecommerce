<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * Membuat instance dari model Product berdasarkan data yang diimport
     */
    public function model(array $row)
    {
        $category = Category::find($row['category_id']);
        if (!$category) {
            return null;
        }

        return new Product([
            'name'        => $row['name'],
            'category_id' => $row['category_id'],
            'description' => $row['description'],
            'price'       => $row['price'],
            'stock'       => $row['stock'],
            'image_url'   => $row['image_url'],
        ]);
    }
}
