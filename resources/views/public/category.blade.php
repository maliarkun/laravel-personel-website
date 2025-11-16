@extends('layouts.app')
@php(use Illuminate\Support\Str;)

@php
    $isGithubCategory = $category->slug === 'github-projects';
    $timelineCollection = $isGithubCategory ? ($timelineProjects ?? collect()) : collect();
@endphp

@section('title', $category->name)

@section('breadcrumb')
<nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500">
    <a href="{{ route('home') }}" class="hover:text-amber-200">{{ __('nav.home') }}</a>
    <span class="mx-2">/</span>
    <span>{{ $category->name }}</span>
</nav>
@endsection

@section('content')
<h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ $category->name }}</h1>
<p class="mt-3 max-w-2xl text-slate-300">{{ $category->description }}</p>

@if($isGithubCategory)
    <section class="mt-10 space-y-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-2xl uppercase tracking-[0.4em] text-slate-100">GitHub Project Timeline</h2>
                <p class="mt-2 text-sm text-slate-300">All repositories, including manually curated projects, are sorted by their latest GitHub activity.</p>
            </div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('category.projects') }}</p>
        </div>

        @php
            $timelineGroups = $timelineCollection->groupBy(function ($project) {
                $date = $project->pushed_at ?? $project->updated_at ?? $project->created_at;

                return optional($date)->format('Y') ?? __('Undated');
            });
        @endphp

        @if($timelineCollection->isNotEmpty())
            <div class="space-y-10">
                @foreach($timelineGroups as $year => $projects)
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-amber-200">{{ $year }}</p>
                        <div class="mt-4 space-y-4">
                            @foreach($projects as $project)
                                @php
                                    $description = $project->summary ?? $project->description;
                                    $lastUpdated = $project->pushed_at ?? $project->updated_at ?? $project->created_at;
                                    $githubLink = $project->github_url ?? $project->repo_url;
                                @endphp
                                <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <h3 class="text-xl uppercase tracking-[0.3em] text-amber-100">{{ $project->title ?? $project->name }}</h3>
                                            @if($description)
                                                <p class="mt-2 text-sm text-slate-300">{{ Str::limit($description, 180) }}</p>
                                            @endif
                                        </div>
                                        @if($githubLink)
                                            <a href="{{ $githubLink }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border border-amber-400 px-4 py-1 text-sm uppercase tracking-[0.3em] text-amber-200 hover:bg-amber-400/10">View on GitHub</a>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs uppercase tracking-[0.3em] text-slate-400">
                                        @if($project->language)
                                            <span>{{ $project->language }}</span>
                                        @endif
                                        @if($project->stars)
                                            <span>⭐ {{ number_format($project->stars) }} stars</span>
                                        @endif
                                        @if($lastUpdated)
                                            <span>Updated {{ $lastUpdated->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                    @if(!empty($project->topics))
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($project->topics as $topic)
                                                <span class="rounded-full bg-slate-800 px-3 py-1 text-xs uppercase tracking-[0.3em] text-slate-200">{{ $topic }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-400">No GitHub projects are available yet. Run <span class="text-amber-200">php artisan github:fetch-projects</span> to sync.</p>
        @endif
    </section>
@else
    <section class="mt-10 space-y-6">
        <h2 class="text-2xl uppercase tracking-[0.4em] text-slate-100">{{ __('category.projects') }}</h2>
        <div class="grid gap-6 md:grid-cols-2">
            @forelse($category->projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 hover:border-amber-300/60">
                    <h3 class="text-xl uppercase tracking-[0.3em] text-amber-100">{{ $project->title }}</h3>
                    <p class="mt-2 text-sm text-slate-300">{{ Str::limit($project->summary, 140) }}</p>
                </a>
            @empty
                <p class="text-sm text-slate-400">{{ __('category.no_projects') }}</p>
            @endforelse
        </div>
    </section>
@endif

<section class="mt-10 space-y-6">
    <h2 class="text-2xl uppercase tracking-[0.4em] text-slate-100">{{ __('category.notes') }}</h2>
    <div class="space-y-4">
        @forelse($category->notes as $note)
            <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg uppercase tracking-[0.3em] text-amber-100">{{ $note->title }}</h3>
                <div class="prose prose-invert mt-3 max-w-none">{!! $note->content !!}</div>
                @if($note->project)
                    <p class="mt-3 text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('category.related_project') }}: <a href="{{ route('projects.show', $note->project) }}" class="text-amber-200 hover:text-amber-100">{{ $note->project->title }}</a></p>
                @endif
            </article>
        @empty
            <p class="text-sm text-slate-400">{{ __('category.no_notes') }}</p>
        @endforelse
    </div>
</section>
@endsection
