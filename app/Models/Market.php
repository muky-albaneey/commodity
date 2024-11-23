<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_id',
        'best_sell',
        'best_buy',
        'market_price',
        'change_24h',
        'volume_24h',
        'buyers_count',
        'sellers_count',
        'market_value'
    ];

    protected $casts = [
        'best_sell' => 'decimal:2',
        'best_buy' => 'decimal:2',
        'market_price' => 'decimal:2',
        'change_24h' => 'decimal:2',
        'volume_24h' => 'decimal:2',
        'market_value' => 'decimal:2'
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }
} 