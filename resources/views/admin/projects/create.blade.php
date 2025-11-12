@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('projects.create_title') }}</h1>

<form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    <x-forms.select name="category_id" :label="__('projects.category')">
        <option value="">{{ __('actions.select') }}</option>
        @foreach($categories as $id => $name)
            <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $name }}</option>
        @endforeach
    </x-forms.select>
    <x-forms.input name="title" :label="__('projects.title')" required />
    <x-forms.input name="slug" :label="__('projects.slug')" />
    <x-forms.textarea name="summary" :label="__('projects.summary')" />
    <x-forms.input type="file" name="featured_image" :label="__('projects.featured_image')" />
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.save') }}</button>
</form>
@endsection
