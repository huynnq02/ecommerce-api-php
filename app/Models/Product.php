<?php
// Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
        'image',
        'amount',
        'rating_average',
        'specifications',
        'highlight',
    ];

    protected $casts = [
        'specifications' => 'json',
        'highlight' => 'json',
    ];
    protected $attributes = [
        'number_of_sold' => 0,
    ];


    // Define the relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
