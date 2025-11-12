<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploadable_id',
        'uploadable_type',
        'path',
        'original_name',
        'size',
    ];

    public function uploadable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }
}
