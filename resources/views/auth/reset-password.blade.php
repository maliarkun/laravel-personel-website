@extends('layouts.app')

@section('title', __('auth.reset'))

@section('content')
<div class="mx-auto max-w-md rounded-3xl border border-slate-800 bg-slate-900/70 p-8">
    <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('auth.reset') }}</h1>
    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-forms.input name="email" type="email" :label="__('auth.email')" value="{{ old('email', $email) }}" required />
        <x-forms.input name="password" type="password" :label="__('auth.password')" required />
        <x-forms.input name="password_confirmation" type="password" :label="__('auth.password_confirm')" required />
        <button class="w-full rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('auth.reset') }}</button>
    </form>
</div>
@endsection
