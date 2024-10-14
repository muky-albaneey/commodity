<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Wallet;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
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

