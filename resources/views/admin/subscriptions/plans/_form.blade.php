@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-1">
        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.plan_management.name') }}</label>
        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm" required>
        @error('name')
            <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-1">
        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.plan_management.slug') }}</label>
        <input type="text" name="slug" value="{{ old('slug', $plan->slug ?? '') }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm" required>
        @error('slug')
            <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-4">
    <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.plan_management.description') }}</label>
    <textarea name="description" rows="3" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">{{ old('description', $plan->description ?? '') }}</textarea>
    @error('description')
        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
    @enderror
</div>

<div class="mt-4 grid gap-4 md:grid-cols-2">
    <div>
        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.plan_management.monthly_price') }}</label>
        <input type="number" step="0.01" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price ?? '') }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
        @error('monthly_price')
            <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.plan_management.yearly_price') }}</label>
        <input type="number" step="0.01" name="yearly_price" value="{{ old('yearly_price', $plan->yearly_price ?? '') }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
        @error('yearly_price')
            <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-4 grid gap-4 md:grid-cols-2">
    <div>
        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">max_projects</label>
        <input type="number" name="limits[max_projects]" value="{{ old('limits.max_projects', $plan->limits['max_projects'] ?? '') }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
    </div>
    <div>
        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">max_notes</label>
        <input type="number" name="limits[max_notes]" value="{{ old('limits.max_notes', $plan->limits['max_notes'] ?? '') }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
    </div>
</div>

<button class="mt-6 w-full rounded-full bg-amber-400/80 px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-950">{{ $buttonLabel }}</button>
