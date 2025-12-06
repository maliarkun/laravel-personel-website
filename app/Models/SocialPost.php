<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'embed_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
