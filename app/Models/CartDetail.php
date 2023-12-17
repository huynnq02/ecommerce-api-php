<?php
// CartDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'cart_detail_id';

    protected $fillable = [
        'product_id',
        'cart_id',
        'quantity',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Define the relationship with the Cart model
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
