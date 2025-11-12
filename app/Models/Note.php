<?php

namespace App\Models;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'project_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    protected static function booted(): void
    {
        static::deleted(function (self $note) {
            if ($note->isForceDeleting()) {
                $note->attachments->each(function (Upload $attachment) {
                    if ($attachment->path) {
                        Storage::disk('public')->delete($attachment->path);
                    }
                    $attachment->delete();
                });
            }
        });
    }
}
