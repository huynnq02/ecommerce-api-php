<?php
// Supplier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
    ];

    // Define the relationship with the Warehouse model
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'supplier_id');
    }
}
