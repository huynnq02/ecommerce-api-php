<?php
// Warehouse.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $primaryKey = 'warehouse_id';
    public $timestamps = false;

    protected $fillable = [
        'warehouse_name',
        'image',
        'location',
        'employee_id',
        'description',
    ];
    // Cast the 'location' attribute as JSON
    protected $casts = [
        'location' => 'json',
        'description' => 'string',
    ];

    // Define the relationship with the Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


    // Define the relationship with the Supplier model
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Define the relationship with the WarehouseDetails model
    public function warehouseDetails()
    {
        return $this->hasMany(WarehouseDetail::class, 'warehouse_id');
    }
}
