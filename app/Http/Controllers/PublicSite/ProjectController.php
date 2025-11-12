<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        $project->load(['category', 'notes' => fn ($query) => $query->latest()]);

        return view('public.project', compact('project'));
    }
}
