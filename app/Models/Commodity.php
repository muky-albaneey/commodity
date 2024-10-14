<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    /** @use HasFactory<\Database\Factories\CommodityFactory> */
    use HasFactory;

    // protected $fillable = ['name', 'description', 'price'];
    protected $fillable = ['name', 'description', 'price', 'image'];
    // Cast price to decimal with 2 decimal places
    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

}
