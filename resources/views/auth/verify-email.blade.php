@extends('layouts.app')

@section('title', __('auth.verify_email'))

@section('content')
<div class="mx-auto max-w-md rounded-3xl border border-slate-800 bg-slate-900/70 p-8 text-center">
    <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('auth.verify_email') }}</h1>
    <p class="mt-4 text-sm text-slate-300">{{ __('auth.verify_notice') }}</p>
    <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('auth.resend') }}</button>
    </form>
</div>
@endsection
