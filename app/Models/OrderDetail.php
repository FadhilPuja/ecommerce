<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'subtotal',
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi dengan Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi dengan Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($orderDetail) {
            if (!$orderDetail->order_id) {
                throw new \Exception("Order ID tidak boleh kosong.");
            }
        });
    }
}
