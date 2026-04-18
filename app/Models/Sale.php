<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'unit_price',
        'total_price',
        'payment_method',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'sold_at'
    ];

    protected $casts = [
        'sold_at' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}