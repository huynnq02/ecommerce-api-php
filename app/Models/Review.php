<?php
// Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'rating_id';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'product_id',
        'customer_id',
        'star',
        'content',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Define the relationship with the Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
