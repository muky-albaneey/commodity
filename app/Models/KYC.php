<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    use HasFactory;

    protected $table = 'kycs';

    protected $fillable = [
        'user_id',
        'id_type',
        'id_number',
        'id_front_image',
        'id_back_image',
        'selfie_image',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}