<?php
// Inquiry.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $primaryKey = 'inquiry_id';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'employee_id',
        'customer_id',
        'star',
        'content',
    ];

    // Define the relationship with the Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Define the relationship with the Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
