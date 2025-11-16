@extends('layouts.app')
@php
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

$markdownAvailable = method_exists(Str::class, 'markdown') && class_exists(\League\CommonMark\MarkdownConverter::class);
@endphp
@section('title', $project->title)

@section('breadcrumb')
@php($category = $project->category)
<nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500">
    <a href="{{ route('home') }}" class="hover:text-amber-200">{{ __('nav.home') }}</a>
    <span class="mx-2">/</span>
    @if($category)
        <a href="{{ route('categories.show', $category) }}" class="hover:text-amber-200">{{ $category->name }}</a>
    @else
        <span>{{ __('project.uncategorized') }}</span>
    @endif
    <span class="mx-2">/</span>
    <span>{{ $project->title }}</span>
</nav>
@endsection

@section('content')
<div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
    <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
        <h1 class="text-4xl uppercase tracking-[0.4em] text-amber-200">{{ $project->title }}</h1>
        <p class="mt-3 text-slate-300">{{ $project->summary }}</p>
        @if($project->featured_image)
            <img src="{{ asset('storage/'.$project->featured_image) }}" alt="{{ $project->title }}" class="mt-6 w-full rounded-3xl border border-slate-800/60" />
        @endif

        @if($project->github_readme)
            <section class="mt-8 rounded-3xl border border-slate-800 bg-slate-950/40 p-6">
                <h2 class="text-sm uppercase tracking-[0.3em] text-amber-200">README</h2>
                <div class="prose prose-invert max-w-none">
                    {!! $markdownAvailable ? Str::markdown($project->github_readme) : nl2br(e($project->github_readme)) !!}
                </div>
            </section>
        @endif

        @if(!empty($project->github_commits))
            <section class="mt-6 rounded-3xl border border-slate-800 bg-slate-950/40 p-6">
                <h2 class="text-sm uppercase tracking-[0.3em] text-amber-200">Son GitHub Aktivitesi</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    @foreach($project->github_commits as $commit)
                        @php
                            $date = !empty($commit['date'])
                                ? Carbon::parse($commit['date'])->format('Y-m-d')
                                : null;
                        @endphp
                        <li class="border-b border-slate-800 pb-2 last:border-none">
                            @if(!empty($commit['url']))
                                <a href="{{ $commit['url'] }}" target="_blank" rel="noopener"
                                   class="text-amber-200 hover:text-amber-100">
                                    {{ Str::limit($commit['message'] ?? 'Commit', 100) }}
                                </a>
                            @else
                                {{ Str::limit($commit['message'] ?? 'Commit', 100) }}
                            @endif

                            <div class="mt-1 text-xs text-slate-500">
                                @if(!empty($commit['author']))
                                    <span>{{ $commit['author'] }}</span>
                                @endif
                                @if($date)
                                    <span class="mx-2">•</span>
                                    <span>{{ $date }}</span>
                                @endif
                                @if(!empty($commit['sha']))
                                    <span class="mx-2">•</span>
                                    <span class="font-mono text-[11px]">
                                        {{ substr($commit['sha'], 0, 7) }}
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </article>
    <aside class="space-y-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h2 class="text-sm uppercase tracking-[0.4em] text-slate-300">{{ __('project.meta') }}</h2>
            <p class="mt-4 text-xs text-slate-400">
                {{ __('project.category') }}:
                @if($category)
                    <a href="{{ route('categories.show', $category) }}" class="text-amber-200 hover:text-amber-100">{{ $category->name }}</a>
                @else
                    <span>{{ __('project.uncategorized') }}</span>
                @endif
            </p>
            <p class="mt-2 text-xs text-slate-400">{{ __('project.created_at') }}: {{ $project->created_at->format('d M Y') }}</p>
        </div>
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h2 class="text-sm uppercase tracking-[0.4em] text-slate-300">{{ __('project.related_notes') }}</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-300">
                @forelse($project->notes as $note)
                    <li class="rounded-full bg-slate-950/50 px-4 py-2">
                        <span class="font-medium text-amber-100">{{ $note->title }}</span>
                        <div class="text-xs text-slate-400">{{ Str::limit(strip_tags($note->content), 80) }}</div>
                    </li>
                @empty
                    <li class="text-xs text-slate-400">{{ __('project.no_notes') }}</li>
                @endforelse
            </ul>
        </div>
    </aside>
</div>
@endsection
