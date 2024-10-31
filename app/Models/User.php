<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Wallet;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guarded = [
    ];

    // Relationship with Wallet
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }



    // Automatically create a wallet for the user after creation
    protected static function booted()
    {
        static::created(function ($user) {
            // Create a wallet for the newly created user
            $user->wallet()->create([
                'balance' => 0.00, // Initial balance for the wallet
            ]);
        });
    }
}