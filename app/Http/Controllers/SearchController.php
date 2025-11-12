<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');

        $projects = collect();
        $notes = collect();

        if ($query) {
            $projects = Project::query()
                ->with('category')
                ->where(function ($builder) use ($query) {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('summary', 'like', "%{$query}%");
                })
                ->get();

            $notes = Note::query()
                ->with(['category', 'project'])
                ->where(function ($builder) use ($query) {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                })
                ->get();
        }

        return view('public.search', compact('query', 'projects', 'notes'));
    }
}
