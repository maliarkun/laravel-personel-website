<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ChronoNotes') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=rajdhani:400,500,700" rel="stylesheet" />
    @vite('resources/js/app.js')
</head>
<body class="min-h-full bg-slate-950 text-slate-100 font-['Rajdhani']">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-amber-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 h-64 w-64 rounded-full bg-slate-500/20 blur-3xl"></div>
        </div>
        <header class="relative z-10 border-b border-slate-800/60 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-[0.3em] uppercase text-amber-300">{{ config('app.name', 'ChronoNotes') }}</a>
                <div class="flex items-center gap-6 text-sm uppercase tracking-[0.3em]">
                    <a href="{{ route('home') }}" class="hover:text-amber-200 transition">{{ __('nav.home') }}</a>
                    <a href="{{ route('search') }}" class="hover:text-amber-200 transition">{{ __('nav.search') }}</a>
                    <a href="{{ route('contact.create') }}" class="hover:text-amber-200 transition">{{ __('nav.contact') }}</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-200 transition">{{ __('nav.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button class="hover:text-amber-200 transition">{{ __('nav.logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-amber-200 transition">{{ __('nav.login') }}</a>
                    @endauth
                    <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                        @foreach(request()->except('lang') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="lang" onchange="this.form.submit()" class="bg-slate-900/80 text-amber-200 rounded-full px-3 py-1">
                            <option value="en" @selected(app()->getLocale()==='en')>EN</option>
                            <option value="tr" @selected(app()->getLocale()==='tr')>TR</option>
                        </select>
                    </form>
                </div>
            </div>
        </header>

        <main class="relative z-10 mx-auto min-h-[70vh] max-w-6xl px-6 py-12">
            @if(session('status'))
                <div class="mb-6 rounded-full border border-amber-400/40 bg-amber-400/10 px-6 py-3 text-sm text-amber-100">
                    {{ session('status') }}
                </div>
            @endif

            @yield('breadcrumb')
            @yield('content')
        </main>

        <footer class="relative z-10 border-t border-slate-800/60 bg-slate-950/80 py-8 text-center text-xs uppercase tracking-[0.3em] text-slate-400">
            © {{ date('Y') }} ChronoNotes. {{ __('nav.footer') }}
        </footer>
    </div>
</body>
</html>
