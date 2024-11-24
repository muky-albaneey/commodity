<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'balance', 'available_balance', 'lien_balance'];

    protected static function booted()
    {
        static::creating(function ($wallet) {
            $wallet->available_balance = $wallet->balance;
            $wallet->lien_balance = 0;
        });

        static::updating(function ($wallet) {
            // When balance changes, update available balance
            if ($wallet->isDirty('balance')) {
                $wallet->available_balance = $wallet->balance - $wallet->lien_balance;
            }
        });
    }

    // A wallet belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to place a hold on funds
    public function placeLien($amount)
    {
        if ($this->available_balance >= $amount) {
            $this->lien_balance += $amount;
            $this->available_balance -= $amount;
            return $this->save();
        }
        return false;
    }

    // Helper method to release held funds
    public function releaseLien($amount)
    {
        if ($this->lien_balance >= $amount) {
            $this->lien_balance -= $amount;
            $this->available_balance += $amount;
            return $this->save();
        }
        return false;
    }
}
