@extends('layouts.app')
@php(use Illuminate\Support\Str;)

@section('title', __('home.title'))

@section('breadcrumb')
<nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('home.breadcrumb') }}</nav>
@endsection

@section('content')
<h1 class="text-5xl font-semibold uppercase tracking-[0.5em] text-amber-200">{{ __('home.heading') }}</h1>
<p class="mt-4 max-w-3xl text-lg text-slate-300">{{ __('home.description') }}</p>

<form action="{{ route('search') }}" class="mt-8 flex items-center gap-3">
    <input type="search" name="q" placeholder="{{ __('home.search_placeholder') }}" class="w-full rounded-full border border-slate-800 bg-slate-950/60 px-6 py-4 text-base" />
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.search') }}</button>
</form>

<div class="mt-10 grid gap-8 md:grid-cols-2">
    @foreach($categories as $category)
        <a href="{{ route('categories.show', $category) }}" class="group relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/70 p-6 transition hover:border-amber-300/60">
            <div class="absolute -top-20 -right-20 h-48 w-48 rounded-full border border-amber-400/40 opacity-0 transition group-hover:opacity-100"></div>
            <h2 class="text-2xl uppercase tracking-[0.4em] text-amber-100">{{ $category->name }}</h2>
            <p class="mt-4 text-sm text-slate-300">{{ Str::limit($category->description, 120) }}</p>
            <div class="mt-6 flex flex-wrap gap-3 text-xs uppercase tracking-[0.3em] text-slate-400">
                <span>{{ trans_choice('home.projects_count', $category->projects_count, ['count' => $category->projects_count]) }}</span>
                <span>{{ trans_choice('home.notes_count', $category->notes_count, ['count' => $category->notes_count]) }}</span>
            </div>
        </a>
    @endforeach
</div>

<div class="mt-10">
    {{ $categories->links() }}
</div>
@endsection
