@extends('layouts.app')

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;

    $isGithubCategory = $category->slug === 'github-projects';

    // Zaman çizelgesi koleksiyonu
    $projects = $isGithubCategory
        ? collect($timelineProjects ?? $category->projects ?? [])
        : collect();

    // En güncel aktiviteye göre sırala
    if ($isGithubCategory) {
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

{{-- ------------------------------
     GITHUB PROJECTS HERO
----------------------------------}}
<section class="mt-4 space-y-4 rounded-3xl border border-slate-800 bg-slate-900/50 p-8 text-slate-100">
    <p class="text-xs uppercase tracking-[0.4em] text-amber-200">GitHub</p>
    <h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-100">
        GitHub Projects
    </h1>
    <p class="max-w-3xl text-base text-slate-300">
        GitHub hesabımdan otomatik olarak içe aktarılan projeler.
        En güncel aktiviteler en üstte listelenir.
    </p>
</section>


{{-- ------------------------------
     PROJE LİSTESİ (KARTLAR)
----------------------------------}}
<section class="mt-10">
    @if($projects->isEmpty())
        <p class="text-sm text-slate-400">
            Henüz herhangi bir GitHub projesi bulunamadı.
            Sunucuda <span class="text-amber-200">php artisan github:fetch-projects</span> komutunu çalıştır.
        </p>
    @else
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($projects as $p)

                @php
                    $title = $p->title ?? $p->name ?? 'Unnamed';
                    $desc  = Str::limit($p->summary ?? $p->description ?? '', 120);
                    $url   = $p->github_url ?? $p->repo_url ?? null;

                    // Tarihi güvenle parse et
                    $rawDate = $p->pushed_at ?? $p->updated_at ?? $p->created_at;
                    try {
                        $updated = $rawDate ? Carbon::parse($rawDate) : null;
                    } catch (\Exception $e) { $updated = null; }

                    $stars = $p->stars ?? $p->stargazers_count ?? null;
                @endphp

                <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 transition hover:border-amber-300/60 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <h3 class="text-lg font-semibold uppercase tracking-[0.2em] text-amber-100">
                            {{ $title }}
                        </h3>

                        {{-- Küçük GitHub simgesi --}}
                        @if($url)
                            <a href="{{ $url }}" target="_blank" class="text-amber-300 hover:text-amber-100" title="GitHub">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
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

                    @if($desc)
                        <p class="mt-3 text-sm text-slate-300 leading-relaxed">{{ $desc }}</p>
                    @endif

                    <div class="mt-4 flex items-center gap-4 text-xs text-slate-500">
                        @if(!empty($p->language))
                            <span>{{ $p->language }}</span>
                        @endif

                        @if($stars)
                            <span>⭐ {{ $stars }}</span>
                        @endif

                        @if($updated)
                            <span>{{ $updated->format('Y-m-d') }}</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

@else
{{-- ---------------------------------------------
     NORMAL KATEGORİ GÖRÜNÜMÜ
---------------------------------------------- --}}
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

{{-- ---------------------------------------------
     NOTES BÖLÜMÜ — GitHub kategorisinde boşsa gizle
---------------------------------------------- --}}
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