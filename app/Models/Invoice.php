<?php
// Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_id';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'total_price',
        'employee_id',
        'customer_id',
        'discount_id',
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

    // Define the relationship with the Discount model
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    // Define the relationship with the InvoiceDetails model
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
}
