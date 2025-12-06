@props(['label', 'value'])
<div
    class="group rounded-3xl border border-slate-800 bg-slate-900/70 p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-slate-700/50 hover:bg-slate-900 hover:shadow-lg hover:shadow-black/20">
    <p
        class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 transition-colors group-hover:text-amber-500/80">
        {{ $label }}</p>
    <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-200 transition-colors group-hover:text-white">
        {{ $value }}</p>
</div>