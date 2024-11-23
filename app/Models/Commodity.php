<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Commodity extends Model
{
    /** @use HasFactory<\Database\Factories\CommodityFactory> */
    use HasFactory;

    protected $fillable = [
        'name',                // Name of the commodity
        'symbol',              // Trading symbol (e.g., SMAZ for Maize)
        'description',         // Description of the commodity
        'image',              // Image path for the commodity
        'minimum_quantity',    // Minimum tradeable quantity
        'maximum_quantity',    // Maximum tradeable quantity
        'category',           // Category (Grains, Oilseeds, etc.)
        'specifications',     // Technical specifications (JSON)
        'trading_hours',      // Trading hours in WAT
        'settlement_type',    // Settlement type (T+2, T+3)
        'contract_size',      // Standard contract size
        'status'             // active/inactive
    ];

    protected $casts = [
        'specifications' => 'array',
        'minimum_quantity' => 'integer',
        'maximum_quantity' => 'integer',
        'status' => 'string'
    ];

    // Set the primary key type to string for UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Automatically generate UUID on creation
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function market()
    {
        return $this->hasOne(Market::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->market?->market_price ?? 0;
    }

    public function getBestBuyAttribute()
    {
        return $this->market?->best_buy ?? 0;
    }

    public function getBestSellAttribute()
    {
        return $this->market?->best_sell ?? 0;
    }

    public function get24hChangeAttribute()
    {
        return $this->market?->change_24h ?? 0;
    }

    public function get24hVolumeAttribute()
    {
        return $this->market?->volume_24h ?? 0;
    }

    public function getMarketValueAttribute()
    {
        return $this->market?->market_value ?? 0;
    }

    public function getActiveBuyersAttribute()
    {
        return $this->market?->buyers_count ?? 0;
    }

    public function getActiveSellersAttribute()
    {
        return $this->market?->sellers_count ?? 0;
    }

    // Helper methods
    public function isTradeableQuantity($quantity): bool
    {
        return $quantity >= $this->minimum_quantity && 
               $quantity <= $this->maximum_quantity;
    }

    public function isActiveForTrading(): bool
    {
        return $this->status === 'active';
    }

    public function getContractSizeInMT(): float
    {
        return (float) str_replace(' MT', '', $this->contract_size);
    }
}
