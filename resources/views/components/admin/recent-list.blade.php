@props(['title', 'items', 'itemTitle' => 'name'])
<div
    class="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 shadow-sm transition-all duration-300 hover:border-slate-700/50 hover:shadow-md">
    <h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-slate-400">
        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
        {{ $title }}
    </h3>
    <ul class="mt-6 space-y-3">
        @forelse($items as $item)
            <li
                class="group flex cursor-default items-center justify-between rounded-2xl bg-slate-950/40 px-5 py-3.5 transition-all duration-300 hover:bg-slate-800/60 hover:pl-6">
                <span
                    class="font-medium text-slate-300 transition-colors group-hover:text-white">{{ data_get($item, $itemTitle) }}</span>
                <span
                    class="text-[10px] font-medium uppercase tracking-wider text-slate-500 transition-colors group-hover:text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li
                class="rounded-2xl border border-dashed border-slate-800 bg-slate-950/20 px-5 py-4 text-center text-xs uppercase tracking-widest text-slate-500">
                {{ __('admin.none') }}</li>
        @endforelse
    </ul>
</div>