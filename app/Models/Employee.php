<?php
// Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'name',
        'phone_number',
        'gender',
        'birthday',
        'address',
    ];

    protected $casts = [
        'address' => 'json',
    ];

    // Define the relationship with the Account model
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // Define the relationship with the Invoice model
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'employee_id');
    }

    // Define the relationship with the Warehouse model
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'employee_id');
    }

    // Define the relationship with the Inquiry model
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'employee_id');
    }
}
