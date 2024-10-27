<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class Commodity extends Model
{
    /** @use HasFactory<\Database\Factories\CommodityFactory> */
    use HasFactory;

    // Define fillable properties
    protected $fillable = [
    
        'name',                // Name of the commodity
        'description',         // Description of the commodity
        'image',               // Image path for the commodity
        'price',               // Price of the commodity
        'market_price',        // Current market price, can fluctuate
        'category',            // Category like precious metals, agriculture, etc.
        'unit',                // Unit of measurement, e.g., kg, oz
        'stock',               // Available stock quantity
        'origin_country',      // Country of origin
        'supplier',            // Supplier name or company
        // 'last_purchased_at',   // Timestamp of the last purchase
        'expiry_date',         // Expiry date if applicable
        // 'tags',                // Tags related to the commodity
        'rating',              // Average rating, e.g., 4.8
        'reviews_count'        // Count of reviews
    ];
    
 
    protected $casts = [
        'price' => 'decimal:2',
        'market_price' => 'decimal:2',
        'rating' => 'decimal:2', // Cast to decimal with 2 decimal places
        'expiry_date' => 'date',
    ];
    
    // Set the primary key type to string for UUID
    protected $keyType = 'string'; 
    public $incrementing = false;  // Disable auto-incrementing

    // Automatically generate UUID on creation
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    // Define relationship with Trade model
    public function trades()
    {
        return $this->hasMany(Trade::class);
    }
}
