<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $primaryKey = 'account_id'; // Primary key field name
    public $timestamps = false; // Disable timestamps if 'created_at' and 'updated_at' are not present in the table

    protected $fillable = [
        'email',
        'password',
        'role',
        'avatar',
        'created_at',
    ];

    // Your relationships, if any
}
