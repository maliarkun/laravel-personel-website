@props(['label', 'name', 'options' => []])
<label class="block space-y-2 text-sm uppercase tracking-[0.3em] text-slate-300">
    <span>{{ $label }}</span>
    <select name="{{ $name }}" {{ $attributes->merge(['class' => 'w-full rounded-full border border-slate-800 bg-slate-950/70 px-5 py-3 text-base text-slate-100 focus:border-amber-300 focus:outline-none']) }}>
        {{ $slot }}
    </select>
    @error($name)
        <span class="text-xs text-rose-300">{{ $message }}</span>
    @enderror
</label>
