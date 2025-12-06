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
                    $term = strtolower($query);
                    $builder->whereRaw('LOWER(title) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(summary) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(topics) LIKE ?', ["%{$term}%"]);

                    foreach ($terms as $t) {
                        if (strlen($t) > 2) {
                            $t = strtolower($t);
                            $builder->orWhereRaw('LOWER(title) LIKE ?', ["%{$t}%"])
                                ->orWhereRaw('LOWER(summary) LIKE ?', ["%{$t}%"])
                                ->orWhereRaw('LOWER(description) LIKE ?', ["%{$t}%"])
                                ->orWhereRaw('LOWER(topics) LIKE ?', ["%{$t}%"]);
                        }
                    }
                })
                // Weighted ordering: Exact(ish) title match > Summary match > Others
                ->orderByRaw("CASE 
                                WHEN LOWER(title) LIKE ? THEN 1 
                                WHEN LOWER(summary) LIKE ? THEN 2 
                                ELSE 3 
                              END", ["%{$query}%", "%{$query}%"])
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            $notes = Note::query()
                ->with(['category', 'project'])
                ->where(function ($builder) use ($query, $terms) {
                    $term = strtolower($query);
                    $builder->whereRaw('LOWER(title) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(content) LIKE ?', ["%{$term}%"]);

                    foreach ($terms as $t) {
                        if (strlen($t) > 2) {
                            $t = strtolower($t);
                            $builder->orWhereRaw('LOWER(title) LIKE ?', ["%{$t}%"])
                                ->orWhereRaw('LOWER(content) LIKE ?', ["%{$t}%"]);
                        }
                    }
                })
                ->orderByRaw("CASE 
                                WHEN LOWER(title) LIKE ? THEN 1 
                                WHEN LOWER(content) LIKE ? THEN 2 
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
