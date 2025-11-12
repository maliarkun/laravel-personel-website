@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
<h1 class="text-4xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('contact.heading') }}</h1>
<p class="mt-4 max-w-2xl text-slate-300">{{ __('contact.description') }}</p>

<form method="POST" action="{{ route('contact.store') }}" class="mt-8 space-y-6 max-w-xl">
    @csrf
    <x-forms.input name="name" :label="__('contact.name')" required />
    <x-forms.input name="email" type="email" :label="__('contact.email')" required />
    <x-forms.textarea name="message" :label="__('contact.message')" rows="6" />
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('contact.submit') }}</button>
</form>
@endsection
