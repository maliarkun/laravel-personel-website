@extends('layouts.app')
@php(use Illuminate\Support\Str;)

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
