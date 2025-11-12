@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('notes.edit_title') }}</h1>

<form method="POST" action="{{ route('admin.notes.update', $note) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    @method('PUT')
    <x-forms.select name="category_id" :label="__('notes.category')">
        @foreach($categories as $id => $name)
            <option value="{{ $id }}" @selected(old('category_id', $note->category_id) == $id)>{{ $name }}</option>
        @endforeach
    </x-forms.select>
    <x-forms.select name="project_id" :label="__('notes.project')">
        <option value="">{{ __('notes.no_project') }}</option>
        @foreach($projects as $id => $name)
            <option value="{{ $id }}" @selected(old('project_id', $note->project_id) == $id)>{{ $name }}</option>
        @endforeach
    </x-forms.select>
    <x-forms.input name="title" :label="__('notes.title')" :value="$note->title" required />
    <x-forms.textarea name="content" :label="__('notes.content')">{{ $note->content }}</x-forms.textarea>
    <x-forms.input type="file" name="attachments[]" :label="__('notes.attachments')" multiple />
    @if($note->attachments->isNotEmpty())
        <div class="space-y-2 text-xs text-slate-400">
            <p>{{ __('notes.current_attachments') }}:</p>
            <ul class="space-y-1">
                @foreach($note->attachments as $attachment)
                    <li><a href="{{ $attachment->url }}" class="text-amber-200 hover:text-amber-100" target="_blank">{{ $attachment->original_name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    <button class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.update') }}</button>
</form>
@endsection
