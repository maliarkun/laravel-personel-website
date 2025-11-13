@extends('layouts.app')
@php
use Illuminate\Support\Str;
@endphp

@section('title', __('search.title'))

@section('content')
<h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('search.heading') }}</h1>
<form class="mt-6 flex items-center gap-3" method="GET" action="{{ route('search') }}">
    <input type="search" name="q" value="{{ $query }}" placeholder="{{ __('search.placeholder') }}" class="w-full rounded-full border border-slate-800 bg-slate-950/60 px-6 py-4 text-base" />
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.search') }}</button>
</form>

<div class="mt-10 grid gap-8 lg:grid-cols-2">
    @if(!$query)
        <p class="col-span-full text-sm text-slate-400">{{ __('search.instructions') }}</p>
    @endif
    <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
        <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('search.projects') }}</h2>
        <ul class="mt-4 space-y-4 text-sm text-slate-300">
            @forelse($projects as $project)
                <li class="rounded-3xl bg-slate-950/50 p-4">
                    <a href="{{ route('projects.show', $project) }}" class="text-amber-100 hover:text-amber-50">{{ $project->title }}</a>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-slate-500">{{ $project->category->name }}</p>
                    <p class="text-xs text-slate-400">{{ Str::limit($project->summary, 100) }}</p>
                </li>
            @empty
                <li class="text-xs text-slate-400">{{ __('search.no_projects') }}</li>
            @endforelse
        </ul>
    </section>
    <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
        <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('search.notes') }}</h2>
        <ul class="mt-4 space-y-4 text-sm text-slate-300">
            @forelse($notes as $note)
                <li class="rounded-3xl bg-slate-950/50 p-4">
                    <p class="font-medium text-amber-100">{{ $note->title }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-slate-500">{{ $note->category->name }}</p>
                    <div class="text-xs text-slate-400">{{ Str::limit(strip_tags($note->content), 120) }}</div>
                </li>
            @empty
                <li class="text-xs text-slate-400">{{ __('search.no_notes') }}</li>
            @endforelse
        </ul>
    </section>
</div>
@endsection
