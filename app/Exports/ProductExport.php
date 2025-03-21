<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /***
    *
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select([
                'category_id', 'name', 'description', 'price',
                'stock', 'image_url', 'created_at', 'updated_at'
            ])->get();
    }

    /**
    *
    * @return array
    */
    public function headings(): array
    {
        return [
            'Product Name', 'Category', 'Description', 'Price',
            'Stock', 'Photo Product', 'Created At', 'Updated At'
        ];
    }

    /**
    *
    * @param  Product  $product
    * @return array
    */
    public function map($product): array
    {
        return [
            $product->name,
            $product->category->name,
            $product->description,
            number_format($product->price, 0, ',', '.'),
            $product->stock,
            $product->image_url ?: 'No Image',
            Carbon::parse($product->created_at)->format('d-m-Y H:i:s'),
            Carbon::parse($product->updated_at)->format('d-m-Y H:i:s'),
        ];
    }
}
