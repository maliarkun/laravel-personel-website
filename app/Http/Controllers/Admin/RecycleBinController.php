<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Note;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecycleBinController extends Controller
{
    protected array $models = [
        'categories' => Category::class,
        'projects' => Project::class,
        'notes' => Note::class,
    ];

    protected array $searchable = [
        'categories' => ['name'],
        'projects' => ['title', 'summary'],
        'notes' => ['title', 'content'],
    ];

    public function index(Request $request): View
    {
        $items = collect($this->models)->mapWithKeys(function (string $model, string $key) use ($request) {
            $query = $model::onlyTrashed()->latest('deleted_at');

            if ($request->filled('search')) {
                $columns = $this->searchable[$key] ?? [];
                if (! empty($columns)) {
                    $search = '%'.$request->string('search').'%';
                    $query->where(function ($q) use ($columns, $search) {
                        foreach ($columns as $index => $column) {
                            $method = $index === 0 ? 'where' : 'orWhere';
                            $q->{$method}($column, 'like', $search);
                        }
                    });
                }
            }

            return [$key => $query->paginate(10, ['*'], $key.'_page')];
        });

        return view('admin.recycle.index', ['items' => $items]);
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $model = $this->resolveModel($type)::onlyTrashed()->findOrFail($id);
        $model->restore();

        return back()->with('status', __('recycle.restored'));
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $model = $this->resolveModel($type)::onlyTrashed()->findOrFail($id);
        $model->forceDelete();

        return back()->with('status', __('recycle.deleted'));
    }

    protected function resolveModel(string $type): string
    {
        if (! array_key_exists($type, $this->models)) {
            abort(404);
        }

        return $this->models[$type];
    }
}
