<?php
// Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'total_price',
        'payment_method',
        'destination',
        'date',
        'status',
    ];

    // Define the relationship with the Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Define the relationship with the OrderDetails model
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
