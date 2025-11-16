@extends('layouts.app')

@section('title', 'Project Timeline')

@section('breadcrumb')
<nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500">Project Timeline</nav>
@endsection

@section('content')
@php
    use Illuminate\Support\Str;
    $currentYear = null;
@endphp

<h1 class="text-5xl font-semibold uppercase tracking-[0.5em] text-amber-200">GitHub Project Timeline</h1>
<p class="mt-4 max-w-3xl text-lg text-slate-300">
    Discover the evolution of my open-source work. Each entry is synced from GitHub and sorted by the latest activity.
</p>

<div class="mt-10 space-y-10">
    @forelse($projects as $project)
        @php
            $timestamp = $project->pushed_at ?? $project->created_at;
            $year = optional($timestamp)->format('Y') ?? 'Unknown';
        @endphp

        @if($year !== $currentYear)
            <div class="pt-4">
                <h2 class="text-3xl uppercase tracking-[0.4em] text-slate-200">{{ $year }}</h2>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-semibold text-amber-100">
                        <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="hover:text-amber-50">
                            {{ $project->name ?? $project->title }}
                        </a>
                    </h3>
                    <p class="mt-2 text-sm text-slate-400">{{ Str::limit($project->description ?? $project->summary, 160) }}</p>
                </div>
                <div class="text-right text-xs uppercase tracking-[0.3em] text-slate-400">
                    <p>Last push</p>
                    <p class="text-base text-amber-200">{{ optional($timestamp)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3 text-sm text-slate-400">
                @if($project->language)
                    <span class="rounded-full border border-slate-800 px-3 py-1 uppercase tracking-[0.3em] text-amber-200">{{ $project->language }}</span>
                @endif
                <span class="rounded-full border border-slate-800 px-3 py-1">⭐ {{ number_format($project->stars ?? 0) }} stars</span>
                <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="rounded-full border border-amber-400 px-4 py-1 text-amber-200 hover:bg-amber-400/10">View on GitHub</a>
            </div>

            @if(!empty($project->topics))
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($project->topics as $topic)
                        <span class="rounded-full bg-slate-800/80 px-3 py-1 text-xs uppercase tracking-[0.3em] text-slate-300">#{{ $topic }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        @php($currentYear = $year)
    @empty
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 text-center text-slate-400">
            No GitHub projects are available yet. Run <span class="text-amber-200">php artisan github:fetch-projects</span> to sync.
        </div>
    @endforelse
</div>
@endsection
