<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Note;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $notes = Note::with(['category', 'project'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%'.$request->string('search').'%')
                        ->orWhere('content', 'like', '%'.$request->string('search').'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.notes.index', compact('notes'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $projects = Project::orderBy('title')->pluck('title', 'id');

        return view('admin.notes.create', compact('categories', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:4096'],
        ]);

        $note = Note::create($data);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('notes', 'public');
                $note->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('admin.notes.index')->with('status', __('notes.created'));
    }

    public function edit(Note $note): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $projects = Project::orderBy('title')->pluck('title', 'id');

        $note->load('attachments');

        return view('admin.notes.edit', compact('note', 'categories', 'projects'));
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:4096'],
        ]);

        $note->update($data);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('notes', 'public');
                $note->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('admin.notes.index')->with('status', __('notes.updated'));
    }

    public function destroy(Note $note): RedirectResponse
    {
        $note->delete();

        return back()->with('status', __('notes.deleted'));
    }
}
