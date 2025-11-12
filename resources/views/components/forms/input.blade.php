@props(['label', 'name', 'type' => 'text'])
<label class="block space-y-2 text-sm uppercase tracking-[0.3em] text-slate-300">
    <span>{{ $label }}</span>
    <input name="{{ $name }}" type="{{ $type }}" @unless(in_array($type, ['file', 'password'], true))value="{{ old($name, $attributes->get('value')) }}"@endunless {{ $attributes->merge(['class' => 'w-full rounded-full border border-slate-800 bg-slate-950/70 px-5 py-3 text-base text-slate-100 focus:border-amber-300 focus:outline-none']) }} />
    @error($name)
        <span class="text-xs text-rose-300">{{ $message }}</span>
    @enderror
</label>
