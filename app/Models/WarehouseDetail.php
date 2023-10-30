<?php
// WarehouseDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'unit',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Define the relationship with the Warehouse model
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
