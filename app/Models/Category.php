<?php
// Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    // Define the relationship with the Products model
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
