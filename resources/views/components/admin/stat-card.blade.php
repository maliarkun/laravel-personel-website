@props(['label', 'value'])
<div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 shadow-[0_10px_40px_-20px_rgba(0,0,0,0.8)]">
    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ $label }}</p>
    <p class="mt-4 text-4xl font-bold text-amber-200">{{ $value }}</p>
</div>
