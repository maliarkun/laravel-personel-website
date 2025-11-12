<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%'.$request->string('search').'%')
                        ->orWhere('summary', 'like', '%'.$request->string('search').'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'unique:projects,slug'],
            'summary' => ['required', 'string'],
            'featured_image' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('status', __('projects.created'));
    }

    public function edit(Project $project): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'unique:projects,slug,'.$project->id],
            'summary' => ['required', 'string'],
            'featured_image' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            if ($project->featured_image) {
                Storage::disk('public')->delete($project->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', __('projects.updated'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return back()->with('status', __('projects.deleted'));
    }
}
