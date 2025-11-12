@props(['title', 'items', 'itemTitle' => 'name'])
<div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
    <h3 class="text-sm uppercase tracking-[0.4em] text-slate-300">{{ $title }}</h3>
    <ul class="mt-4 space-y-3">
        @forelse($items as $item)
            <li class="flex items-center justify-between rounded-full bg-slate-950/50 px-4 py-3">
                <span>{{ data_get($item, $itemTitle) }}</span>
                <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li class="rounded-full bg-slate-950/50 px-4 py-3 text-sm text-slate-400">{{ __('admin.none') }}</li>
        @endforelse
    </ul>
</div>
