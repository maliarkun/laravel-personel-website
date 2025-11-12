<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::with([
                'projects' => fn ($q) => $q->latest()->limit(5),
                'notes' => fn ($q) => $q->latest()->limit(5),
            ])
            ->withCount(['projects', 'notes'])
            ->orderBy('name')
            ->paginate(6);

        return view('public.home', compact('categories'));
    }

    public function show(Category $category): View
    {
        $category->load([
            'projects.notes' => fn ($query) => $query->latest(),
            'notes' => fn ($query) => $query->latest(),
            'notes.project',
        ]);

        return view('public.category', compact('category'));
    }
}
