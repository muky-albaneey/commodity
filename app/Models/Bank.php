<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // A bank can have many bank accounts
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
} 