@extends('layouts.admin')

@section('title', 'Edit Social Post')

@section('admin-content')
    <div class="mb-6">
        <a href="{{ route('admin.social-posts.index') }}"
            class="text-slate-400 hover:text-white transition uppercase tracking-widest text-xs">&larr; Back to List</a>
        <h1 class="mt-2 text-2xl font-bold text-slate-100 uppercase tracking-widest">Edit Post</h1>
    </div>

    <div class="max-w-3xl rounded-3xl border border-slate-800 bg-slate-900/70 p-8">
        <form action="{{ route('admin.social-posts.update', $socialPost) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Platform</label>
                <select name="platform"
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-amber-500">
                    <option value="twitter" @selected($socialPost->platform == 'twitter')>Twitter / X</option>
                    <option value="linkedin" @selected($socialPost->platform == 'linkedin')>LinkedIn</option>
                    <option value="instagram" @selected($socialPost->platform == 'instagram')>Instagram</option>
                    <option value="other" @selected($socialPost->platform == 'other')>Other</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Embed Code</label>
                <textarea name="embed_code" rows="5"
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-amber-500 font-mono text-sm">{{ $socialPost->embed_code }}</textarea>
            </div>

            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked($socialPost->is_active)
                        class="w-5 h-5 rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500/50">
                    <span class="text-slate-300 group-hover:text-white transition">Active / Published</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-amber-500 text-slate-900 font-bold uppercase tracking-widest py-4 rounded-xl hover:bg-amber-400 transition">
                Update Post
            </button>
        </form>
    </div>
@endsection