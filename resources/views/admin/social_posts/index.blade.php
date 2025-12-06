@extends('layouts.admin')

@section('title', 'Social Media Posts')

@section('admin-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-100 uppercase tracking-widest">Social Media Posts</h1>
        <a href="{{ route('admin.social-posts.create') }}"
            class="px-6 py-2 bg-amber-500 text-slate-900 font-bold rounded-full hover:bg-amber-400 transition">
            ADD NEW
        </a>
    </div>

    <div class="rounded-3xl border border-slate-800 bg-slate-900/70 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-950/50 text-xs uppercase tracking-wider text-slate-400">
                <tr>
                    <th class="px-6 py-4">Platform</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Created At</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($posts as $post)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="px-6 py-4 font-medium text-slate-200">{{ $post->platform }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold {{ $post->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                {{ $post->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-sm">{{ $post->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.social-posts.edit', $post) }}"
                                class="text-amber-400 hover:text-amber-300 transition text-sm font-bold uppercase tracking-wider">Edit</a>
                            <form action="{{ route('admin.social-posts.destroy', $post) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-400 hover:text-red-300 transition text-sm font-bold uppercase tracking-wider">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-6">
            {{ $posts->links() }}
        </div>
    </div>
@endsection