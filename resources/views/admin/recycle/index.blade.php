@extends('layouts.admin')

@section('admin-content')
<h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('admin.recycle') }}</h1>

<form method="GET" class="mt-6">
    <input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('actions.search') }}" class="w-full rounded-full border border-slate-800 bg-slate-950/70 px-5 py-3 text-sm" />
</form>

<div class="mt-6 space-y-6">
    @foreach($items as $type => $records)
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70">
            <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
                <h2 class="text-sm uppercase tracking-[0.3em] text-slate-300">{{ __('admin.recycle_section', ['type' => __('admin.' . $type)]) }}</h2>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($records as $item)
                    <div class="flex flex-wrap items-center justify-between px-6 py-4 text-sm">
                        <div>
                            <p class="font-medium text-amber-200">{{ $item->title ?? $item->name }}</p>
                            <p class="text-xs text-slate-400">{{ __('admin.deleted_at', ['date' => $item->deleted_at->diffForHumans()]) }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('admin.recycle.restore', [$type, $item->id]) }}">
                                @csrf
                                <button class="rounded-full border border-emerald-400 px-4 py-2 text-xs uppercase tracking-[0.3em] text-emerald-200 hover:bg-emerald-400/10">{{ __('actions.restore') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.recycle.destroy', [$type, $item->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-full border border-rose-400 px-4 py-2 text-xs uppercase tracking-[0.3em] text-rose-200 hover:bg-rose-400/10" onclick="return confirm('{{ __('actions.confirm_force_delete') }}')">{{ __('actions.force_delete') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-slate-400">{{ __('admin.recycle_empty') }}</p>
                @endforelse
            </div>
            <div class="px-6 py-4">
                {{ $records->appends(request()->except($type.'_page'))->links() }}
            </div>
        </div>
    @endforeach
</div>
@endsection
