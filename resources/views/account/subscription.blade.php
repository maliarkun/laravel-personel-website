@extends('layouts.app')

@section('title', __('account.subscription_heading'))

@section('content')
    @php
        $subscription = auth()->user()->activeSubscription;
        $plan = $subscription?->plan;
    @endphp

    <div class="space-y-8">
        <div class="rounded-3xl border border-slate-800/60 bg-slate-900/40 p-8 shadow-lg shadow-slate-950/40">
            <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold uppercase tracking-[0.35em] text-amber-300">{{ __('account.subscription_heading') }}</h1>
                    <p class="mt-2 text-sm text-slate-400">{{ __('account.subscription_description') }}</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-xs uppercase tracking-[0.35em] text-amber-200">
                    {{ $subscription?->status ? ucfirst($subscription->status) : __('account.no_plan') }}
                </span>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.current_plan') }}</p>
                        <p class="mt-2 text-lg font-semibold text-amber-200">{{ $plan?->name ?? 'Free' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.plan_status') }}</p>
                        <p class="mt-2 text-sm text-slate-200">{{ $subscription?->status ? ucfirst($subscription->status) : __('account.no_plan') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('account.plan_valid_until') }}</p>
                        <p class="mt-2 text-sm text-slate-200">{{ optional($subscription?->ends_at)->translatedFormat('d M Y') ?? __('account.no_plan') }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-800/80 bg-slate-950/60 p-6 text-sm text-slate-300">
                    <p class="font-semibold uppercase tracking-[0.3em] text-amber-200">{{ __('account.manage_plan') }}</p>
                    <p class="mt-3 text-slate-400">{{ __('account.dummy_notice') }}</p>
                    <ul class="mt-4 space-y-2 text-xs uppercase tracking-[0.3em] text-slate-400">
                        <li>• Upgrade, downgrade or cancel anytime</li>
                        <li>• Upcoming invoices preview</li>
                        <li>• Usage counters (projects, notes, storage)</li>
                    </ul>
                    <button class="mt-6 w-full rounded-full bg-slate-800 px-6 py-3 text-xs font-semibold uppercase tracking-[0.35em] text-amber-200 transition hover:bg-slate-700" type="button" disabled>
                        {{ __('account.manage_plan') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
