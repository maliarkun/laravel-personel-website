<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialPost;
use Illuminate\Http\Request;

class SocialPostController extends Controller
{
    public function index()
    {
        $posts = SocialPost::latest()->paginate(10);
        return view('admin.social_posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.social_posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'embed_code' => 'required|string',
            'is_active' => 'boolean',
        ]);

        SocialPost::create($validated);

        return redirect()->route('admin.social_posts.index')
            ->with('status', 'Social post created successfully.');
    }

    public function edit(SocialPost $socialPost)
    {
        return view('admin.social_posts.edit', compact('socialPost'));
    }

    public function update(Request $request, SocialPost $socialPost)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'embed_code' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $socialPost->update($validated);

        return redirect()->route('admin.social_posts.index')
            ->with('status', 'Social post updated successfully.');
    }

    public function destroy(SocialPost $socialPost)
    {
        $socialPost->delete();
        return back()->with('status', 'Social post deleted successfully.');
    }
}
