<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'model_number',
        'price',
        'quantity',
        'category_id',
        'image',
        'performance',
        // Dynamic fields will be stored as JSON
        'dynamic_fields'
    ];

    protected $casts = [
        'dynamic_fields' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}