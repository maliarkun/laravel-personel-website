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

        {{-- GitHub Sync Status --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h3 class="text-sm uppercase tracking-[0.4em] text-slate-300">GitHub Sync Status</h3>
            <div class="mt-6 flex flex-col items-center justify-center space-y-4 py-4">
                @if($githubLastSync)
                    <div class="relative">
                        <div
                            class="absolute -inset-1 rounded-full {{ $githubSyncStatus === 'success' ? 'bg-green-500' : 'bg-red-500' }} opacity-20 blur">
                        </div>
                        <div
                            class="h-24 w-24 rounded-full border-2 {{ $githubSyncStatus === 'success' ? 'border-green-500 text-green-400' : 'border-red-500 text-red-400' }} flex items-center justify-center bg-slate-950 text-3xl">
                            <i class="fa-brands fa-github"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-slate-200 uppercase tracking-widest">
                            {{ $githubSyncStatus === 'success' ? 'Synced' : 'Failed' }}</p>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest">Last Update</p>
                        <p class="text-sm text-slate-300">{{ \Carbon\Carbon::parse($githubLastSync)->diffForHumans() }}</p>
                    </div>
                @else
                    <div class="text-slate-500 text-sm uppercase tracking-widest">No sync data available</div>
                @endif
            </div>
        </div>
    </div>
@endsection