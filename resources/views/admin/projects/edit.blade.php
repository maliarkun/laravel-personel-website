@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('projects.edit_title') }}</h1>

<form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    @method('PUT')
    <x-forms.select name="category_id" :label="__('projects.category')">
        @foreach($categories as $id => $name)
            <option value="{{ $id }}" @selected(old('category_id', $project->category_id) == $id)>{{ $name }}</option>
        @endforeach
    </x-forms.select>
    <x-forms.input name="title" :label="__('projects.title')" :value="$project->title" required />
    <x-forms.input name="slug" :label="__('projects.slug')" :value="$project->slug" />
    <x-forms.textarea name="summary" :label="__('projects.summary')">{{ $project->summary }}</x-forms.textarea>
    <x-forms.input type="file" name="featured_image" :label="__('projects.featured_image')" />
    @if($project->featured_image)
        <p class="text-xs text-slate-400">{{ __('projects.current_image') }}: <a href="{{ asset('storage/'.$project->featured_image) }}" target="_blank" class="text-amber-200 hover:text-amber-100">{{ __('actions.view') }}</a></p>
    @endif
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.update') }}</button>
</form>
@endsection
