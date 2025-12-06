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
        <div
            class="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 shadow-sm transition-all duration-300 hover:border-slate-700/50 hover:shadow-md">
            <h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-slate-400">
                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                Top Searches
            </h3>
            <ul class="mt-6 space-y-3">
                @forelse($topSearches as $search)
                    <li
                        class="group flex items-center justify-between rounded-2xl bg-slate-950/40 px-5 py-3.5 transition-all duration-300 hover:bg-slate-800/60 hover:pl-6">
                        <span
                            class="font-medium text-amber-100 transition-colors group-hover:text-amber-50">{{ $search->term }}</span>
                        <span
                            class="rounded-md bg-amber-500/10 px-2.5 py-1 text-xs font-bold text-amber-500 group-hover:bg-amber-500/20">{{ $search->count }}</span>
                    </li>
                @empty
                    <li
                        class="rounded-2xl border border-dashed border-slate-800 bg-slate-950/20 px-5 py-4 text-center text-xs uppercase tracking-widest text-slate-500">
                        {{ __('admin.none') }}</li>
                @endforelse
            </ul>
        </div>

        {{-- GitHub Sync Status --}}
        <div
            class="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 shadow-sm transition-all duration-300 hover:border-slate-700/50 hover:shadow-md">
            <h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-slate-400">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                GitHub Status
            </h3>
            <div class="mt-8 flex flex-col items-center justify-center space-y-6">
                @if($githubLastSync)
                    <div class="relative group/icon cursor-default">
                        <div
                            class="absolute -inset-4 rounded-full {{ $githubSyncStatus === 'success' ? 'bg-green-500' : 'bg-red-500' }} opacity-10 blur-xl transition-opacity duration-500 group-hover/icon:opacity-20">
                        </div>
                        <div
                            class="relative h-24 w-24 rounded-full border border-white/5 bg-gradient-to-br from-slate-900 to-slate-950 shadow-2xl flex items-center justify-center text-4xl transition-transform duration-500 group-hover/icon:scale-110">
                            <i
                                class="fa-brands fa-github {{ $githubSyncStatus === 'success' ? 'text-green-400' : 'text-red-400' }}"></i>

                            <div
                                class="absolute -bottom-1 -right-1 h-8 w-8 rounded-full border-4 border-slate-900 {{ $githubSyncStatus === 'success' ? 'bg-green-500' : 'bg-red-500' }}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center space-y-1">
                        <p class="text-xl font-bold tracking-tight text-slate-100">
                            {{ $githubSyncStatus === 'success' ? 'All Systems Operational' : 'Sync Failed' }}
                        </p>
                        <p class="text-xs font-medium uppercase tracking-widest text-slate-500">Last Synced
                            {{ \Carbon\Carbon::parse($githubLastSync)->diffForHumans() }}</p>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-800 bg-slate-950/20 px-8 py-6 text-center">
                        <p class="text-xs uppercase tracking-widest text-slate-500">No sync data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection