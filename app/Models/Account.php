<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable implements JWTSubject
{
    use Notifiable;

    use HasFactory;
    protected $primaryKey = 'account_id'; // Primary key field name
    public $timestamps = false; // Disable timestamps if 'created_at' and 'updated_at' are not present in the table

    protected $fillable = [
        'email',
        'password',
        'role',
        'avatar',
        'created_at',
        'customer',

    ];

    // Define the relationship with the Customer model
    public function customer()
    {
        return $this->hasOne(Customer::class, 'account_id');
    }
    public function employee()
    {
        return $this->hasOne(employee::class, 'account_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
