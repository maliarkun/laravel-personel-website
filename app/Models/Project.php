<?php

namespace App\Models;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'featured_image',
        'category_id',
        'name',
        'description',
        'repo_name',
        'repo_url',
        'github_url',
        'stars',
        'topics',
        'language',
        'pushed_at',
        'created_at',
        'github_readme',
        'github_commits',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'pushed_at' => 'datetime',
        'topics' => 'array',
        'stars' => 'integer',
        'github_commits' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function attachments()
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    protected static function booted(): void
    {
        static::deleted(function (self $project) {
            if ($project->isForceDeleting()) {
                if ($project->featured_image) {
                    Storage::disk('public')->delete($project->featured_image);
                }

                $project->attachments->each(function (Upload $attachment) {
                    if ($attachment->path) {
                        Storage::disk('public')->delete($attachment->path);
                    }
                    $attachment->delete();
                });
            }
        });
    }
}
