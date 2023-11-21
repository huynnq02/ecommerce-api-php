<?php
// Discount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $primaryKey = 'discount_id';
    public $timestamps = false;
    protected $table = 'discounts';
    protected $fillable = [
        'discount_value',
        'code',
        'start_day',
        'end_day',
    ];

    // Define the relationship with the Carts model
    public function carts()
    {
        return $this->hasMany(Cart::class, 'discount_id');
    }

    // Define the relationship with the Invoices model
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'discount_id');
    }
}
