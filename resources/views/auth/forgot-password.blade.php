@extends('layouts.app')

@section('title', __('auth.forgot'))

@section('content')
<div class="mx-auto max-w-md rounded-3xl border border-slate-800 bg-slate-900/70 p-8">
    <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('auth.forgot') }}</h1>
    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-6">
        @csrf
        <x-forms.input name="email" type="email" :label="__('auth.email')" value="{{ old('email') }}" required />
        <button class="w-full rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('auth.send_link') }}</button>
    </form>
</div>
@endsection
