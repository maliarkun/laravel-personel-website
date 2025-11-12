@props(['label', 'name'])
<label class="block space-y-2 text-sm uppercase tracking-[0.3em] text-slate-300">
    <span>{{ $label }}</span>
    <textarea name="{{ $name }}" rows="6" {{ $attributes->merge(['class' => 'w-full rounded-3xl border border-slate-800 bg-slate-950/70 px-5 py-3 text-base text-slate-100 focus:border-amber-300 focus:outline-none']) }}>{{ old($name, $attributes->get('value', $slot)) }}</textarea>
    @error($name)
        <span class="text-xs text-rose-300">{{ $message }}</span>
    @enderror
</label>
