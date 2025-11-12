<?php

namespace App\Models;

use App\Models\Note;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    protected static function booted(): void
    {
        static::deleted(function (self $category) {
            if ($category->isForceDeleting()) {
                $category->projects()->withTrashed()->get()->each(function (Project $project) {
                    $project->forceDelete();
                });

                $category->notes()->withTrashed()->get()->each(function (Note $note) {
                    $note->forceDelete();
                });
            }
        });
    }
}
