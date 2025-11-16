<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ProjectTimelineController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()
            ->route('categories.show', ['category' => 'github-projects'])
            ->setStatusCode(301);
    }
}
