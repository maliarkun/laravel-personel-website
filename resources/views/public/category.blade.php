@extends('layouts.app')

@php
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
@endphp

@php
    $isGithubCategory = $category->slug === 'github-projects';

    // Zaman çizelgesi için temel koleksiyon:
    // Controller'dan $timelineProjects gelmişse onu kullan,
    // yoksa kategoriye bağlı projeleri yedek olarak kullan.
    $baseCollection = $isGithubCategory
        ? (($timelineProjects ?? null) instanceof \Illuminate\Support\Collection
            ? $timelineProjects
            : collect($timelineProjects ?? $category->projects ?? []))
        : collect();

    $timelineCollection = $isGithubCategory ? $baseCollection : collect();
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
@if($isGithubCategory)
    {{-- GITHUB PROJECTS HERO --}}
    <section class="mt-4 space-y-6 rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900/80 to-slate-900/30 p-8 text-slate-100">
        <p class="text-xs uppercase tracking-[0.4em] text-amber-200">Özenle seçilmiş</p>
        <h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-100">GitHub Projects</h1>
        <p class="max-w-3xl text-base text-slate-300">
            GitHub hesabımdan otomatik olarak içe aktarılan projeler. Denemelerim, kütüphanelerim ve oynama alanlarımın güncel hâllerini buradan takip edebilirsin.
        </p>
        <p class="text-sm text-slate-400">
            Projeler GitHub’daki en son aktivite tarihine göre sıralanır; böylece şu anda hangi işleri kurcaladığıma hızlıca bakabilirsin.
        </p>
    </section>

    @php
        $githubProjects = $timelineCollection
            ->sortByDesc(function ($project) {
                return $project->pushed_at ?? $project->updated_at ?? $project->created_at;
            })
            ->values();
    @endphp

    <section class="mt-10 space-y-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-2xl uppercase tracking-[0.4em] text-slate-100">{{ __('category.projects') }}</h2>
                <p class="text-sm text-slate-400">
                    En güncel aktiviteden eskiye doğru sıralanmış GitHub projeleri.
                </p>
            </div>
            <span class="text-xs uppercase tracking-[0.3em] text-slate-500">
                {{ $githubProjects->count() }} repository
            </span>
        </div>

        @if($githubProjects->isEmpty())
            <p class="text-sm text-slate-400">
                Henüz herhangi bir GitHub projesi senkronize edilmemiş.
                Sunucuda <span class="text-amber-200">php artisan github:fetch-projects</span> komutunu çalıştırarak projeleri çekebilirsin.
            </p>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($githubProjects as $project)
                    @php
                        $description = $project->summary ?? $project->description ?? null;
                        $githubLink  = $project->github_url ?? $project->repo_url ?? null;

                        $rawLastUpdated = $project->pushed_at ?? $project->updated_at ?? $project->created_at ?? null;
                        if ($rawLastUpdated instanceof Carbon) {
                            $lastUpdated = $rawLastUpdated;
                        } elseif (!empty($rawLastUpdated)) {
                            try {
                                $lastUpdated = Carbon::parse($rawLastUpdated);
                            } catch (\Exception $e) {
                                $lastUpdated = null;
                            }
                        } else {
                            $lastUpdated = null;
                        }

                        $stars = $project->stars
                            ?? $project->stargazers_count
                            ?? null;

                        // topics alanı JSON, collection veya array olabilir
                        $topics = [];
                        if (!empty($project->topics)) {
                            if (is_array($project->topics)) {
                                $topics = $project->topics;
                            } elseif ($project->topics instanceof \Illuminate\Support\Collection) {
                                $topics = $project->topics->all();
                            } elseif (is_string($project->topics)) {
                                $decoded = json_decode($project->topics, true);
                                $topics = is_array($decoded) ? $decoded : [];
                            }
                        }
                    @endphp

                    <article class="flex flex-col rounded-3xl border border-slate-800 bg-slate-900/70 p-6 transition hover:-translate-y-1 hover:border-amber-300/60">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-xl uppercase tracking-[0.3em] text-amber-100">
                                    {{ $project->title ?? $project->name ?? 'Untitled Project' }}
                                </h3>

                                @if($githubLink)
                                    <a href="{{ $githubLink }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="inline-flex items-center gap-1 rounded-full border border-amber-400 px-3 py-1 text-xs uppercase tracking-[0.3em] text-amber-200 hover:bg-amber-400/10">
                                        View on GitHub
                                    </a>
                                @endif
                            </div>

                            @if($description)
                                <p class="text-sm text-slate-300">
                                    {{ Str::limit($description, 160) }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-4 text-xs uppercase tracking-[0.3em] text-slate-400">
                            @if(!empty($project->language))
                                <span>{{ $project->language }}</span>
                            @endif

                            @if(!empty($stars))
                                <span>⭐ {{ number_format($stars) }}</span>
                            @endif

                            @if($lastUpdated)
                                <span>Updated {{ $lastUpdated->format('Y-m-d') }}</span>
                            @endif
                        </div>

                        @if(!empty($topics))
                            <div class="mt-4 flex flex-wrap gap-2 text-xs uppercase tracking-[0.3em] text-slate-200">
                                @foreach($topics as $topic)
                                    <span class="rounded-full bg-slate-800 px-3 py-1">
                                        {{ $topic }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@else
    {{-- NORMAL KATEGORİ GÖRÜNÜMÜ --}}
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
@endif

{{-- NOTES BÖLÜMÜ: GitHub kategorisinde ve boşken gizle --}}
@if(!($isGithubCategory && $category->notes->isEmpty()))
    <section class="mt-10 space-y-6">
        <h2 class="text-2xl uppercase tracking-[0.4em] text-slate-100">{{ __('category.notes') }}</h2>
        <div class="space-y-4">
            @forelse($category->notes as $note)
                <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                    <h3 class="text-lg uppercase tracking-[0.3em] text-amber-100">{{ $note->title }}</h3>
                    <div class="prose prose-invert mt-3 max-w-none">{!! $note->content !!}</div>
                    @if($note->project)
                        <p class="mt-3 text-xs uppercase tracking-[0.3em] text-slate-400">
                            {{ __('category.related_project') }}:
                            <a href="{{ route('projects.show', $note->project) }}" class="text-amber-200 hover:text-amber-100">
                                {{ $note->project->title }}
                            </a>
                        </p>
                    @endif
                </article>
            @empty
                <p class="text-sm text-slate-400">{{ __('category.no_notes') }}</p>
            @endforelse
        </div>
    </section>
@endif
@endsection