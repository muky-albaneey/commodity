<?php

namespace App\Models;

use App\Models\Trade;
use App\Models\Wallet;
use App\Models\BillingAddress;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $appends = ['tradesCount', 'totalTradeVolume'];

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'phoneNumber',
        'address',
        'state',
        'currency',
        'country',
        'isAdmin',
        'isSuspend',
        'customerID',
    ];

    protected $guarded = [
        'isAdmin',
        'isSuspend',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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

    public function billingAddress()
    {
        return $this->hasMany(BillingAddress::class);
    }

    // Add these accessor methods
    public function getTradesCountAttribute()
    {
        return $this->trades()->count();
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function kyc()
    {
        return $this->hasOne(KYC::class);
    }

    public function hasCompletedKYC()
    {
        return $this->kyc && $this->kyc->status === 'approved';
    }

    public function hasSetPin()
    {
        return !empty($this->pin) && $this->has_set_pin;
    }

    public function hasBankAccount()
    {
        return $this->bankAccounts()->where('is_verified', true)->exists();
    }

    public function getTotalTradeVolumeAttribute()
    {
        return $this->trades()->sum('total_price');
    }

    // Generate a unique customer ID
    private function generateUniqueCustomerID()
    {
        do {
            $number = mt_rand(100000, 999999); // Generate random 6-digit number
            $customerID = '#' . $number;
        } while (static::where('customerID', $customerID)->exists());

        return $customerID;
    }

    // Automatically create a wallet for the user after creation
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->customerID = $user->generateUniqueCustomerID();
        });

        static::created(function ($user) {
            // Create a wallet for the newly created user
            $user->wallet()->create([
                'balance' => 0.00, // Initial balance for the wallet
            ]);
        });
    }

    // Add these relationships
public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}