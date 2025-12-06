<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::with([
            'projects' => fn($q) => $q->latest()->limit(5),
            'notes' => fn($q) => $q->latest()->limit(5),
        ])
            ->withCount(['projects', 'notes'])
            ->orderBy('name')
            ->paginate(6);

        $socialPosts = \App\Models\SocialPost::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('public.home', compact('categories', 'socialPosts'));
    }

    public function show(Category $category): View
    {
        $category->load([
            'projects.notes' => fn($query) => $query->latest(),
            'notes' => fn($query) => $query->latest(),
            'notes.project',
        ]);

        $timelineProjects = null;

        if ($category->slug === 'github-projects') {
            $timelineProjects = Project::query()
                ->where('category_id', $category->id)
                ->orderByDesc('pushed_at')
                ->orderByDesc('updated_at')
                ->orderByDesc('created_at')
                ->get();
        }

        return view('public.category', compact('category', 'timelineProjects'));
    }
}
