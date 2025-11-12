@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('notes.create_title') }}</h1>

<form method="POST" action="{{ route('admin.notes.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    <x-forms.select name="category_id" :label="__('notes.category')">
        <option value="">{{ __('actions.select') }}</option>
        @foreach($categories as $id => $name)
            <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $name }}</option>
        @endforeach
    </x-forms.select>
    <x-forms.select name="project_id" :label="__('notes.project')">
        <option value="">{{ __('notes.no_project') }}</option>
        @foreach($projects as $id => $name)
            <option value="{{ $id }}" @selected(old('project_id') == $id)>{{ $name }}</option>
        @endforeach
    </x-forms.select>
    <x-forms.input name="title" :label="__('notes.title')" required />
    <x-forms.textarea name="content" :label="__('notes.content')" />
    <x-forms.input type="file" name="attachments[]" :label="__('notes.attachments')" multiple />
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.save') }}</button>
</form>
@endsection
