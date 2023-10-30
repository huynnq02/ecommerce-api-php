<?php
// InvoiceDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'invoice_id',
        'quantity',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Define the relationship with the Invoice model
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
