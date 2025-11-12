@extends('layouts.app')

@section('title', __('profile.title'))

@section('content')
<div class="mx-auto max-w-2xl space-y-8">
    <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
        <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('profile.update_details') }}</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('PATCH')
            <x-forms.input name="name" :label="__('profile.name')" :value="$user->name" required />
            <x-forms.input name="email" type="email" :label="__('profile.email')" :value="$user->email" required />
            <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('profile.save') }}</button>
        </form>
    </section>
    <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
        <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('profile.delete_title') }}</h2>
        <p class="mt-2 text-xs text-slate-400">{{ __('profile.delete_description') }}</p>
        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4 space-y-4">
            @csrf
            @method('DELETE')
            <x-forms.input name="password" type="password" :label="__('auth.password')" required />
            <button class="rounded-full border border-rose-400 px-8 py-3 text-sm uppercase tracking-[0.3em] text-rose-200 hover:bg-rose-400/10" onclick="return confirm('{{ __('profile.confirm_delete') }}')">{{ __('profile.delete_account') }}</button>
        </form>
    </section>
</div>
@endsection
