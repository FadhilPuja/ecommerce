<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil data kategori
     */
    public function collection()
    {
        return Category::select('name', 'created_at', 'updated_at')->get();
    }

    /**
     * Set Header Column di Excel
     */
    public function headings(): array
    {
        return [
            'Category Name',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Format data sebelum diekspor
     */
    public function map($category): array
    {
        return [
            $category->name,
            $category->created_at ? $category->created_at->format('d-m-Y H:i:s') : '-',
            $category->updated_at ? $category->updated_at->format('d-m-Y H:i:s') : '-',
        ];
    }
}
