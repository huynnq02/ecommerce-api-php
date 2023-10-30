<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = false; // Disable timestamps if 'created_at' and 'updated_at' are not present in the table

    protected $primaryKey = 'customer_id'; // Primary key field name

    protected $fillable = [
        'account_id',
        'name',
        'phone_number',
        'gender',
        'birthday',
        'address',
    ];

    protected $casts = [
        'address' => 'json', // Cast the 'address' attribute to JSON
    ];

    // Define the relationship with the Account model
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
