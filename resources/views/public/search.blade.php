@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', __('search.title'))

@section('content')
    <h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('search.heading') }}</h1>
    <form class="mt-6 flex items-center gap-3" method="GET" action="{{ route('search') }}">
        <input type="search" name="q" value="{{ $query }}" placeholder="{{ __('search.placeholder') }}"
            class="w-full rounded-full border border-slate-800 bg-slate-950/60 px-6 py-4 text-base" />
        <button
            class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.search') }}</button>
    </form>

    @php
        function highlight($text, $query)
        {
            if (!$query)
                return $text;
            return preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="bg-amber-500/40 text-white px-0.5 rounded">$1</span>', $text);
        }
    @endphp

    <div class="mt-10 grid gap-8 lg:grid-cols-2">
        @if(!$query)
            <p class="col-span-full text-sm text-slate-400">{{ __('search.instructions') }}</p>
        @endif
        <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('search.projects') }} <span
                    class="text-slate-600">({{ $projects->count() }})</span></h2>
            <ul class="mt-4 space-y-4 text-sm text-slate-300">
                @forelse($projects as $project)
                    <li class="rounded-3xl bg-slate-950/50 p-4 border border-slate-800/50 transition hover:border-amber-500/30">
                        <a href="{{ route('projects.show', $project) }}"
                            class="text-amber-100 font-bold hover:text-amber-50 block mb-1">
                            {!! highlight($project->title, $query) !!}
                        </a>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-slate-500">{{ $project->category->name }}</p>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            {!! highlight(Str::limit($project->summary, 140), $query) !!}
                        </p>
                    </li>
                @empty
                    <li class="text-xs text-slate-400 p-4 border border-dashed border-slate-800 rounded-xl">
                        {{ __('search.no_projects') }}</li>
                @endforelse
            </ul>
        </section>
        <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('search.notes') }} <span
                    class="text-slate-600">({{ $notes->count() }})</span></h2>
            <ul class="mt-4 space-y-4 text-sm text-slate-300">
                @forelse($notes as $note)
                    <li class="rounded-3xl bg-slate-950/50 p-4 border border-slate-800/50 transition hover:border-amber-500/30">
                        <p class="font-bold text-amber-100 mb-1">{!! highlight($note->title, $query) !!}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-slate-500">{{ $note->category->name }}</p>
                        <div class="text-xs text-slate-400 mt-2 leading-relaxed font-mono">
                            {!! highlight(Str::limit(strip_tags($note->content), 140), $query) !!}
                        </div>
                    </li>
                @empty
                    <li class="text-xs text-slate-400 p-4 border border-dashed border-slate-800 rounded-xl">
                        {{ __('search.no_notes') }}</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection