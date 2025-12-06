@extends('layouts.admin')

@section('admin-content')
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat-card :label="__('admin.categories')" :value="$categoryCount" />
        <x-admin.stat-card :label="__('admin.projects')" :value="$projectCount" />
        <x-admin.stat-card :label="__('admin.notes')" :value="$noteCount" />
        <x-admin.stat-card :label="__('admin.users')" :value="$userCount" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-admin.recent-list :title="__('admin.recent_projects')" :items="$recentProjects" item-title="title" />
        <x-admin.recent-list :title="__('admin.recent_notes')" :items="$recentNotes" item-title="title" />

        {{-- Search Stats --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h3 class="text-sm uppercase tracking-[0.4em] text-slate-300">Top Searches</h3>
            <ul class="mt-4 space-y-3">
                @forelse($topSearches as $search)
                    <li class="flex items-center justify-between rounded-full bg-slate-950/50 px-4 py-3">
                        <span>{{ $search->term }}</span>
                        <span class="rounded-full bg-amber-500/20 px-2 py-1 text-xs text-amber-300">{{ $search->count }}</span>
                    </li>
                @empty
                    <li class="rounded-full bg-slate-950/50 px-4 py-3 text-sm text-slate-400">{{ __('admin.none') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection