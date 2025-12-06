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
        <div class="space-y-2">
            <label class="block text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('projects.description') }}</label>
            <textarea name="description" id="editor"
                class="w-full rounded-3xl border border-slate-800 bg-slate-950/70 px-5 py-3 text-base text-slate-100 focus:border-amber-300 focus:outline-none">{{ old('description') }}</textarea>
        </div>
        <x-forms.input type="file" name="featured_image" :label="__('projects.featured_image')" />
        <button
            class="rounded-full border border-amber-400 px-8 py-3 text-sm uppercase tracking-[0.3em] hover:bg-amber-400/10">{{ __('actions.save') }}</button>
    </form>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
            background-color: #0f172a !important;
            /* slate-900 */
            color: #e2e8f0 !important;
            /* slate-200 */
            border-radius: 0 0 1rem 1rem !important;
            border-color: #334155 !important;
        }

        .ck-toolbar {
            background-color: #1e293b !important;
            /* slate-800 */
            border-color: #334155 !important;
            border-radius: 1rem 1rem 0 0 !important;
        }

        /* Toolbar Icon Colors */
        .ck.ck-icon {
            color: #cbd5e1 !important;
            /* slate-300 */
        }

        .ck.ck-button {
            color: #cbd5e1 !important;
            cursor: pointer !important;
        }

        .ck.ck-button:hover,
        .ck.ck-button.ck-on {
            background-color: #334155 !important;
            /* slate-700 */
            color: #f8fafc !important;
            /* slate-50 */
        }

        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
            border-color: #334155 !important;
        }

        /* Dropdowns (Headers, etc.) */
        .ck.ck-dropdown__panel {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        .ck.ck-list__item .ck-button:hover {
            background-color: #334155 !important;
        }
    </style>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection