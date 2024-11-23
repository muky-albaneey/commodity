<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = [
        'user_id',
        'commodity_id',
        'trade_type',
        'quantity',
        'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }
}