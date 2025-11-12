@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
<div class="mx-auto max-w-md rounded-3xl border border-slate-800 bg-slate-900/70 p-8">
    <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('auth.login') }}</h1>
    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-6">
        @csrf
        <x-forms.input name="email" type="email" :label="__('auth.email')" value="{{ old('email') }}" required autofocus />
        <x-forms.input name="password" type="password" :label="__('auth.password')" required />
        <label class="flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-slate-400">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border border-slate-700 bg-slate-900" />
            {{ __('auth.remember') }}
        </label>
        <button class="w-full rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('auth.login') }}</button>
    </form>
    <div class="mt-6 text-center text-xs uppercase tracking-[0.3em] text-slate-400">
        <a href="{{ route('password.request') }}" class="hover:text-amber-200">{{ __('auth.forgot') }}</a>
    </div>
</div>
@endsection
