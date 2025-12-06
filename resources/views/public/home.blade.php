@extends('layouts.app')

@section('title', __('home.title'))

@section('breadcrumb')
    <nav class="mb-6 text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('home.breadcrumb') }}</nav>
@endsection

@section('content')
    <div class="relative">
        {{-- Hero Section --}}
        <div class="py-12 md:py-20 text-center relative z-10">
            <h1
                class="text-6xl md:text-8xl font-bold uppercase tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-500 drop-shadow-lg motion-safe:animate-bounce-slow">
                {{ __('home.heading') }}
            </h1>
            <p class="mt-6 mx-auto max-w-2xl text-xl text-slate-400 font-light tracking-wide leading-relaxed">
                {{ __('home.description') }}
            </p>

            {{-- Search "Capsule" --}}
            <form action="{{ route('search') }}"
                class="group relative mt-12 mx-auto max-w-xl transition-all duration-500 hover:-translate-y-1">
                <div
                    class="absolute -inset-1 rounded-full bg-gradient-to-r from-amber-500 via-purple-500 to-amber-500 opacity-30 blur transition group-hover:opacity-75">
                </div>
                <div class="relative flex items-center rounded-full bg-slate-950 p-2 shadow-2xl">
                    <input type="search" name="q" placeholder="{{ __('home.search_placeholder') }}"
                        class="w-full bg-transparent border-none px-6 py-3 text-lg text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-0" />
                    <button
                        class="shrink-0 rounded-full bg-amber-500 px-8 py-3 text-xs font-bold uppercase tracking-[0.2em] text-slate-950 transition hover:bg-amber-400 hover:shadow-[0_0_20px_rgba(251,191,36,0.5)]">
                        {{ __('actions.search') }}
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- Social Gravity Stream --}}
    @if($socialPosts->isNotEmpty())
        <div class="mt-24 mb-12">
            <h2 class="text-center text-sm font-bold uppercase tracking-[0.5em] text-slate-500 mb-10">Signals from Orbit</h2>

            <div class="flex flex-wrap justify-center gap-6">
                @foreach($socialPosts as $post)
                    <div class="w-full md:w-[22rem] group relative">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl blur opacity-30 group-hover:opacity-75 transition duration-500">
                        </div>
                        <div
                            class="relative h-full bg-slate-900 rounded-2xl p-6 ring-1 ring-white/10 overflow-hidden hover:-translate-y-2 transition-transform duration-500">

                            {{-- Platform Icon/Label --}}
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="text-xs font-mono text-slate-400 px-2 py-1 rounded bg-slate-800 uppercase">{{ $post->platform }}</span>
                                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                            </div>

                            {{-- Social Content Container --}}
                            <div class="prose prose-invert prose-sm max-w-none text-slate-300 font-sans">
                                {!! $post->embed_code !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Floating Grid --}}
    <div class="mt-20 grid gap-8 md:grid-cols-2 lg:grid-cols-3 perspective-1000">
        @foreach($categories as $category)
            <a href="{{ route('categories.show', $category) }}"
                class="group relative overflow-hidden rounded-[2rem] border border-white/5 bg-white/5 p-8 transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)] hover:bg-white/10 backdrop-blur-sm">

                {{-- Floating Orb in Card --}}
                <div
                    class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-gradient-to-br from-amber-500/20 to-purple-500/20 blur-2xl transition-all duration-700 group-hover:scale-150 group-hover:bg-amber-500/30">
                </div>

                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <h2
                            class="text-3xl font-bold uppercase tracking-[0.2em] text-slate-100 group-hover:text-amber-300 transition-colors">
                            {{ $category->name }}
                        </h2>
                        <div
                            class="mt-1 h-1 w-12 bg-amber-500/50 transition-all duration-500 group-hover:w-full group-hover:bg-amber-400">
                        </div>
                        <p class="mt-6 text-sm leading-relaxed text-slate-400 group-hover:text-slate-200 transition-colors">
                            {{ \Illuminate\Support\Str::limit($category->description, 140) }}
                        </p>
                    </div>

                    <div class="mt-8 flex items-center justify-between border-t border-white/5 pt-6">
                        <div class="flex gap-4 text-[10px] uppercase tracking-[0.3em] text-slate-500">
                            <div class="flex items-center gap-2">
                                <span class="block h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                {{ trans_choice('home.projects_count', $category->projects_count, ['count' => $category->projects_count]) }}
                            </div>
                        </div>
                        <span
                            class="text-amber-500 opacity-0 transition-all duration-300 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0">
                            &rarr;
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-16 text-center">
        {{ $categories->links() }}
    </div>
    </div>
@endsection