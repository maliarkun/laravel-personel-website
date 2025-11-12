@extends('layouts.admin')

@section('admin-content')
<div class="flex items-center justify-between">
    <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('admin.categories') }}</h1>
    <a href="{{ route('admin.categories.create') }}" class="rounded-full border border-amber-400 px-6 py-2 text-sm uppercase tracking-[0.4em] hover:bg-amber-400/10">{{ __('actions.create') }}</a>
</div>

<form method="GET" class="mt-6">
    <input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('actions.search') }}" class="w-full rounded-full border border-slate-800 bg-slate-950/70 px-5 py-3 text-sm" />
</form>

<div class="mt-6 overflow-hidden rounded-3xl border border-slate-800">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-900/70 uppercase tracking-[0.3em] text-slate-400">
            <tr>
                <th class="px-6 py-4">{{ __('categories.name') }}</th>
                <th class="px-6 py-4">{{ __('categories.projects_count') }}</th>
                <th class="px-6 py-4">{{ __('categories.notes_count') }}</th>
                <th class="px-6 py-4 text-right">{{ __('actions.actions') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr class="border-t border-slate-800/60">
                <td class="px-6 py-4">{{ $category->name }}</td>
                <td class="px-6 py-4">{{ $category->projects_count }}</td>
                <td class="px-6 py-4">{{ $category->notes_count }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="mr-3 text-amber-200 hover:text-amber-100">{{ __('actions.edit') }}</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-rose-300 hover:text-rose-100" onclick="return confirm('{{ __('actions.confirm_delete') }}')">{{ __('actions.delete') }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $categories->withQueryString()->links() }}
</div>
@endsection
