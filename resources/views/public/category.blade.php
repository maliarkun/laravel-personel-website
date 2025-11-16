@extends('layouts.app')

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;

    $isGithubCategory = $category->slug === 'github-projects';

    // GitHub kategorisi için kullanılacak proje koleksiyonu
    $projects = $isGithubCategory
        ? collect($timelineProjects ?? $category->projects ?? [])
        : collect();

    if ($isGithubCategory) {
        // En güncel GitHub aktivitesine göre sırala
        $projects = $projects->sortByDesc(function ($p) {
            return $p->pushed_at ?? $p->updated_at ?? $p->created_at;
        })->values();
    }
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

    {{-- GITHUB PROJECTS - HEADER --}}
    <section class="mt-4 space-y-4 rounded-3xl border border-slate-800 bg-slate-900/50 p-8 text-slate-100">
        <p class="text-xs uppercase tracking-[0.4em] text-amber-200">GitHub</p>
        <h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-100">
            GitHub Projects
        </h1>
        <p class="max-w-3xl text-base text-slate-300">
            GitHub hesabımdan otomatik olarak içe aktarılan projeler.
            En güncel aktivitelere göre sıralanmıştır.
        </p>
    </section>

    {{-- GITHUB PROJECT LIST (temayla uyumlu kartlar) --}}
    <section class="mt-10 space-y-6">
        <div class="flex items-center justify-between text-xs text-slate-500">
            <span class="uppercase tracking-[0.3em]">{{ __('category.projects') }}</span>
            <span class="uppercase tracking-[0.3em]">{{ $projects->count() }} repositories</span>
        </div>

        @if($projects->isEmpty())
            <p class="text-sm text-slate-400">
                Henüz herhangi bir GitHub projesi bulunamadı.
                Sunucuda <span class="text-amber-200">php artisan github:fetch-projects</span> komutunu çalıştırarak projeleri senkronize edebilirsin.
            </p>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($projects as $p)
                    @php
                        $title = $p->title ?? $p->name ?? 'Unnamed';
                        $desc  = Str::limit($p->summary ?? $p->description ?? '', 140);
                        $url   = $p->github_url ?? $p->repo_url ?? null;

                        // Tarih (son aktivite) – önce pushed_at, sonra updated_at, yoksa created_at
                        $rawDate = $p->pushed_at ?? $p->updated_at ?? $p->created_at;
                        try {
                            $updated = $rawDate ? Carbon::parse($rawDate) : null;
                        } catch (\Exception $e) {
                            $updated = null;
                        }

                        // Dil
                        $language = $p->language ?? null;

                        // Yıldız sayısını olabildiğince yakala
                        $rawStars = $p->stars
                            ?? $p->stargazers_count
                            ?? $p->stargazers
                            ?? null;
                        $stars = is_numeric($rawStars) ? (int) $rawStars : null;

                        // Fork ve issue sayıları – varsa gösterilecek
                        $forks  = $p->forks ?? $p->forks_count ?? null;
                        $issues = $p->open_issues ?? $p->open_issues_count ?? null;
                    @endphp

                    <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 hover:border-amber-300/60 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                {{-- Başlık: detay sayfasına link --}}
                                <a href="{{ route('projects.show', $p) }}" class="block">
                                    <h3 class="text-xl uppercase tracking-[0.3em] text-amber-100">
                                        {{ $title }}
                                    </h3>
                                </a>

                                {{-- Açıklama --}}
                                @if($desc)
                                    <p class="mt-2 text-sm text-slate-300">
                                        {{ $desc }}
                                    </p>
                                @endif
                            </div>

                            {{-- Sağ üst: GitHub ikonu --}}
                            @if($url)
                                <a href="{{ $url }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="text-amber-300 hover:text-amber-100"
                                   title="GitHub">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 0C5.37 0 0 5.48 0 12.25c0 5.4 3.44 9.97 8.2 11.59.6.11.82-.27.82-.6 
                                            0-.3-.01-1.09-.02-2.14-3.34.75-4.04-1.66-4.04-1.66-.55-1.43-1.34-1.81-1.34-1.81-1.1-.77.08-.76.08-.76 
                                            1.22.09 1.86 1.28 1.86 1.28 1.08 1.91 2.82 1.36 3.51 1.04.11-.81.42-1.36.76-1.67-2.67-.31-5.47-1.38-5.47-6.15 
                                            0-1.36.46-2.47 1.22-3.34-.12-.32-.53-1.57.12-3.27 0 0 1-.33 3.3 1.27a11.08 11.08 0 0 1 6 0C18 5.3 19 5.63 19 5.63 
                                            c.66 1.7.25 2.95.12 3.27a4.91 4.91 0 0 1 1.22 3.34c0 4.79-2.81 5.84-5.49 6.15.43.38.82 1.13.82 2.3 
                                            0 1.67-.01 3.01-.01 3.42 0 .33.22.72.83.6A12.27 12.27 0 0 0 24 12.25C24 5.48 18.63 0 12 0z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        {{-- 1. satır: tarihi öne çıkar --}}
                        @if($updated)
                            <div class="mt-4 text-xs font-semibold text-amber-300">
                                Updated {{ $updated->format('Y-m-d') }}
                            </div>
                        @endif

                        {{-- 2. satır: dil • yıldız • forks • issues --}}
                        <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                            @if($language)
                                <span>{{ $language }}</span>
                            @endif

                            {{-- Yıldız mutlaka görünsün (sayı yoksa sadece ikon) --}}
                            @if($stars !== null && $stars > 0)
                                <span>• ⭐ {{ $stars }}</span>
                            @else
                                <span>• ⭐</span>
                            @endif

                            @if(!is_null($forks))
                                <span>• Forks {{ $forks }}</span>
                            @endif

                            @if(!is_null($issues))
                                <span>• Issues {{ $issues }}</span>
                            @endif
                        </div>
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