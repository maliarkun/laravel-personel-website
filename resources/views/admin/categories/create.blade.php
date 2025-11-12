@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('categories.create_title') }}</h1>

<form method="POST" action="{{ route('admin.categories.store') }}" class="mt-6 space-y-6">
    @csrf
    <x-forms.input name="name" :label="__('categories.name')" required />
    <x-forms.input name="slug" :label="__('categories.slug')" />
    <x-forms.textarea name="description" :label="__('categories.description')" />
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.save') }}</button>
</form>
@endsection
