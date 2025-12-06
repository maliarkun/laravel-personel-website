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
            $terms = explode(' ', $query);

            $projects = Project::query()
                ->with('category')
                ->where(function ($builder) use ($query, $terms) {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('summary', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");

                    foreach ($terms as $term) {
                        if (strlen($term) > 2) {
                            $builder->orWhere('title', 'like', "%{$term}%")
                                ->orWhere('summary', 'like', "%{$term}%");
                        }
                    }
                })
                // Weighted ordering: Exact(ish) title match > Summary match > Others
                ->orderByRaw("CASE 
                                WHEN title LIKE ? THEN 1 
                                WHEN summary LIKE ? THEN 2 
                                ELSE 3 
                              END", ["%{$query}%", "%{$query}%"])
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            $notes = Note::query()
                ->with(['category', 'project'])
                ->where(function ($builder) use ($query, $terms) {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");

                    foreach ($terms as $term) {
                        if (strlen($term) > 2) {
                            $builder->orWhere('title', 'like', "%{$term}%")
                                ->orWhere('content', 'like', "%{$term}%");
                        }
                    }
                })
                ->orderByRaw("CASE 
                                WHEN title LIKE ? THEN 1 
                                WHEN content LIKE ? THEN 2 
                                ELSE 3 
                              END", ["%{$query}%", "%{$query}%"])
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            // Log the search
            if (strlen($query) > 2) {
                \App\Models\SearchLog::create([
                    'term' => $query,
                    'ip' => $request->ip(),
                    'user_id' => auth()->id(),
                    'results_count' => $projects->count() + $notes->count(),
                ]);
            }
        }

        return view('public.search', compact('query', 'projects', 'notes'));
    }
}
