@extends('layouts.admin')

@section('admin-content')
    <div class="rounded-3xl border border-slate-800/70 bg-slate-900/60 p-8">
        <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('admin.plan_management.edit') }}</h1>
        <form method="POST" action="{{ route('admin.subscriptions.plans.update', $plan) }}" class="mt-6 space-y-4">
            @method('PUT')
            @include('admin.subscriptions.plans._form', ['plan' => $plan, 'buttonLabel' => __('admin.plan_management.save')])
        </form>
    </div>
@endsection
