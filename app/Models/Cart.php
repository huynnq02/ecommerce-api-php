<?php
// Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'discount_id',
        'total_price',
    ];

    // Define the relationship with the Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Define the relationship with the Discount model
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    // Define the relationship with the CartDetails model
    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class, 'cart_id');
    }
}
