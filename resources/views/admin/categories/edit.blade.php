@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('categories.edit_title') }}</h1>

<form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mt-6 space-y-6">
    @csrf
    @method('PUT')
    <x-forms.input name="name" :label="__('categories.name')" :value="$category->name" required />
    <x-forms.input name="slug" :label="__('categories.slug')" :value="$category->slug" />
    <x-forms.textarea name="description" :label="__('categories.description')">{{ $category->description }}</x-forms.textarea>
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.update') }}</button>
</form>
@endsection
