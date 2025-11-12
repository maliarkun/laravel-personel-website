@extends('layouts.app')
@php(use Illuminate\Support\Str;)

@section('title', $project->title)

@section('breadcrumb')
<nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500">
    <a href="{{ route('home') }}" class="hover:text-amber-200">{{ __('nav.home') }}</a>
    <span class="mx-2">/</span>
    <a href="{{ route('categories.show', $project->category) }}" class="hover:text-amber-200">{{ $project->category->name }}</a>
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
    </article>
    <aside class="space-y-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h2 class="text-sm uppercase tracking-[0.4em] text-slate-300">{{ __('project.meta') }}</h2>
            <p class="mt-4 text-xs text-slate-400">{{ __('project.category') }}: <a href="{{ route('categories.show', $project->category) }}" class="text-amber-200 hover:text-amber-100">{{ $project->category->name }}</a></p>
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
