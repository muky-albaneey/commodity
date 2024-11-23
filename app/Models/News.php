<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag',
        'title',
        'description',
        'image',
        'link',
        'author',
        'readTime',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'readTime' => 'integer',
    ];
}
