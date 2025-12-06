<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    protected $fillable = ['term', 'ip', 'user_id', 'results_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
