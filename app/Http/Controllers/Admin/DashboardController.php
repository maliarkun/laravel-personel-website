<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Note;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'categoryCount' => Category::count(),
            'projectCount' => Project::count(),
            'noteCount' => Note::count(),
            'userCount' => User::count(),
            'recentProjects' => Project::latest()->take(5)->get(),
            'recentNotes' => Note::latest()->take(5)->get(),
        ]);
    }
}
