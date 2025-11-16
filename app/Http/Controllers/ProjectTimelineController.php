<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectTimelineController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->whereNotNull('repo_name')
            ->orderByDesc('pushed_at')
            ->orderByDesc('updated_at')
            ->get();

        return view('projects.timeline', [
            'projects' => $projects,
        ]);
    }
}
