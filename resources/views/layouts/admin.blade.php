@extends('layouts.app')

@section('title', __('nav.dashboard'))

@section('content')
<div class="grid gap-8 lg:grid-cols-[18rem_1fr]">
    <aside class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
        <nav class="space-y-4 text-sm uppercase tracking-[0.3em]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.dashboard') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.dashboard') }}</span>
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.categories.*') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.categories') }}</span>
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
            <a href="{{ route('admin.projects.index') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.projects.*') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.projects') }}</span>
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
            <a href="{{ route('admin.notes.index') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.notes.*') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.notes') }}</span>
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
            <a href="{{ route('admin.recycle.index') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.recycle.*') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.recycle') }}</span>
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.users.*') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.user_management.title') }}</span> {{-- Yeni kullanıcı yönetimi bağlantısı --}}
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
            <a href="{{ route('admin.subscriptions.plans.index') }}" class="flex items-center justify-between rounded-full px-4 py-2 hover:bg-amber-400/10 {{ request()->routeIs('admin.subscriptions.plans.*') ? 'text-amber-200' : '' }}">
                <span>{{ __('admin.plan_management.title') }}</span> {{-- Plan yönetimi için kısayol --}}
                <span class="h-3 w-3 rounded-full border border-amber-300"></span>
            </a>
        </nav>
    </aside>
    <section class="space-y-6">
        @yield('admin-content')
    </section>
</div>
@endsection
