@extends('layouts.admin')

@section('admin-content')
    <div class="space-y-8">
        <div class="flex items-center justify-between rounded-3xl border border-slate-800/70 bg-slate-900/60 p-8">
            <div>
                <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('admin.plan_management.title') }}</h1>
                <p class="mt-2 text-sm text-slate-400">Design plans for future billing integrations.</p>
            </div>
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="rounded-full bg-amber-400/80 px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-950">
                {{ __('admin.plan_management.create') }}
            </a>
        </div>

        <div class="space-y-4">
            @foreach($plans as $plan)
                <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6 shadow-lg shadow-slate-950/30">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-amber-200">{{ $plan->name }}</h2>
                            <p class="text-sm text-slate-400">{{ $plan->description }}</p>
                            <p class="mt-2 text-xs uppercase tracking-[0.35em] text-slate-500">Slug: {{ $plan->slug }}</p>
                        </div>
                        <div class="text-sm text-right text-slate-400">
                            <p>{{ __('admin.plan_management.monthly_price') }}: <span class="text-amber-200">{{ $plan->monthly_price ?? '—' }}</span></p>
                            <p>{{ __('admin.plan_management.yearly_price') }}: <span class="text-amber-200">{{ $plan->yearly_price ?? '—' }}</span></p>
                            <p>{{ __('admin.plan_management.limits') }}: <span class="text-amber-200">{{ collect($plan->limits)->map(fn($value, $key) => $key . ':' . $value)->implode(', ') ?: '—' }}</span></p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}" class="rounded-full bg-slate-800 px-5 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-200 hover:bg-slate-700">{{ __('admin.plan_management.edit') }}</a>
                            <form method="POST" action="{{ route('admin.subscriptions.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-full bg-rose-500/80 px-5 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-50 hover:bg-rose-400">{{ __('admin.plan_management.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $plans->links() }}
        </div>
    </div>
@endsection
