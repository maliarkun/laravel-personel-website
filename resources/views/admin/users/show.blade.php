@extends('layouts.admin')

@section('admin-content')
    <div class="space-y-8">
        <div class="rounded-3xl border border-slate-800/70 bg-slate-900/60 p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ $user->name }}</h1>
                    <p class="text-sm text-slate-400">{{ $user->email }}</p>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500 mt-2">{{ $user->roles->pluck('name')->map(fn ($role) => ucfirst($role))->implode(', ') }}</p>
                </div>
                <div class="text-right text-sm text-slate-400">
                    <p>{{ __('profile.last_login') }}: <span class="text-amber-200">{{ optional($user->last_login_at)->diffForHumans() ?? '—' }}</span></p>
                    <p>IP: <span class="text-amber-200">{{ $user->last_login_ip ?? '—' }}</span></p>
                    <p>{{ __('admin.user_management.status') }}: <span class="text-amber-200">{{ $user->is_locked ? __('admin.user_management.locked') : __('admin.user_management.active') }}</span></p>
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6">
                    <h2 class="text-lg font-semibold uppercase tracking-[0.35em] text-amber-200">{{ __('admin.user_management.assign_roles') }}</h2>
                    <form method="POST" action="{{ route('admin.users.roles', $user) }}" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="space-y-2">
                            @foreach(['admin', 'editor', 'member'] as $role)
                                <label class="flex items-center gap-3 text-sm text-slate-300">
                                    <input type="checkbox" name="roles[]" value="{{ $role }}" class="rounded border-slate-700 bg-slate-900" @checked($user->hasRole($role))>
                                    <span class="uppercase tracking-[0.3em]">{{ ucfirst($role) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <button class="w-full rounded-full bg-amber-400/80 px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-950">{{ __('admin.user_management.assign_roles') }}</button>
                    </form>
                </div>

                <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6">
                    <h2 class="text-lg font-semibold uppercase tracking-[0.35em] text-amber-200">{{ __('admin.user_management.lock_account') }}</h2>
                    <form method="POST" action="{{ route('admin.users.lock', $user) }}" class="mt-4">
                        @csrf
                        @method('PUT')
                        <button class="w-full rounded-full {{ $user->is_locked ? 'bg-rose-500/80 hover:bg-rose-400' : 'bg-slate-800 hover:bg-slate-700' }} px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-100">
                            {{ $user->is_locked ? __('auth.unlock') : __('admin.user_management.lock_account') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6">
                <h2 class="text-lg font-semibold uppercase tracking-[0.35em] text-amber-200">{{ __('admin.user_management.update_plan') }}</h2>
                <form method="POST" action="{{ route('admin.users.plan', $user) }}" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.plan_management.name') }}</label>
                        <select name="plan_id" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                            <option value="">Free</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.user_management.status') }}</label>
                        <select name="status" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                            @foreach(['active', 'trial', 'cancelled'] as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">Start</label>
                        <input type="date" name="starts_at" value="{{ now()->toDateString() }}" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-[0.35em] text-slate-400">End</label>
                        <input type="date" name="ends_at" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                    </div>
                    <button class="w-full rounded-full bg-amber-400/80 px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-950">{{ __('admin.user_management.update_plan') }}</button>
                </form>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold uppercase tracking-[0.35em] text-amber-200">{{ __('admin.user_management.subscriptions') }}</h2>
            <div class="mt-4 space-y-3 text-sm text-slate-300">
                @forelse($user->subscriptions as $subscription)
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4">
                        <p><span class="text-slate-400">{{ __('admin.plan_management.name') }}:</span> {{ $subscription->plan?->name ?? 'Free' }}</p>
                        <p><span class="text-slate-400">{{ __('admin.user_management.status') }}:</span> {{ ucfirst($subscription->status) }}</p>
                        <p><span class="text-slate-400">{{ __('admin.user_management.period') }}:</span> {{ $subscription->starts_at->toDateString() }} - {{ optional($subscription->ends_at)->toDateString() ?? '∞' }}</p>
                    </div>
                @empty
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500">{{ __('admin.none') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6">
            <h2 class="text-lg font-semibold uppercase tracking-[0.35em] text-amber-200">{{ __('admin.user_management.activities') }}</h2>
            <div class="mt-4 space-y-3 text-sm text-slate-300">
                @forelse($user->activityLogs as $log)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3">
                        <span>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                        <span class="text-xs uppercase tracking-[0.35em] text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500">{{ __('admin.none') }}</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
