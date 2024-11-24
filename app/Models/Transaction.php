<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'bank_account_id',
        'transaction_hash',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Define the possible transaction types
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';

    // Define the possible transaction statuses
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Transaction belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Transaction belongs to a bank account
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    // Scope for deposits
    public function scopeDeposits($query)
    {
        return $query->where('type', self::TYPE_DEPOSIT);
    }

    // Scope for withdrawals
    public function scopeWithdrawals($query)
    {
        return $query->where('type', self::TYPE_WITHDRAWAL);
    }

    // Scope for pending transactions
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Scope for completed transactions
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Scope for failed transactions
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    // Helper method to generate transaction hash
    public static function generateTransactionHash()
    {
        return substr(md5(uniqid()), 0, 12);
    }

    // Helper method to check if transaction is pending
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Helper method to check if transaction is completed
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Helper method to check if transaction is failed
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    // Helper method to check if transaction is a deposit
    public function isDeposit()
    {
        return $this->type === self::TYPE_DEPOSIT;
    }

    // Helper method to check if transaction is a withdrawal
    public function isWithdrawal()
    {
        return $this->type === self::TYPE_WITHDRAWAL;
    }
} 