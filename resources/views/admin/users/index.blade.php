@extends('layouts.admin')

@section('admin-content')
    <div class="space-y-8">
        <div class="rounded-3xl border border-slate-800/70 bg-slate-900/60 p-8">
            <h1 class="text-3xl font-semibold uppercase tracking-[0.4em] text-amber-200">{{ __('admin.user_management.title') }}</h1>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-4">
                <div>
                    <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.user_management.role') }}</label>
                    <select name="role" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                        <option value="">--</option>
                        @foreach(['admin', 'editor', 'member'] as $role)
                            <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.user_management.plan') }}</label>
                    <select name="plan" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                        <option value="">--</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->slug }}" @selected(request('plan') === $plan->slug)>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ __('admin.user_management.status') }}</label>
                    <select name="status" class="mt-2 w-full rounded-xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-sm">
                        <option value="">--</option>
                        @foreach(['active', 'trial', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="w-full rounded-full bg-amber-400/80 px-4 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-950">{{ __('admin.user_management.apply_filters') }}</button>
                    <a href="{{ route('admin.users.index') }}" class="rounded-full bg-slate-800 px-4 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-200">{{ __('admin.user_management.reset_filters') }}</a>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($users as $user)
                <div class="rounded-3xl border border-slate-800/70 bg-slate-900/50 p-6 shadow-lg shadow-slate-950/30">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-amber-200">{{ $user->name }}</h2>
                            <p class="text-sm text-slate-400">{{ $user->email }}</p>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-500 mt-2">{{ $user->roles->pluck('name')->map(fn ($role) => ucfirst($role))->implode(', ') ?: __('admin.user_management.member') }}</p>
                        </div>
                        <div class="text-sm text-right text-slate-400">
                            <p>{{ __('admin.user_management.plan') }}: <span class="text-amber-200">{{ $user->activeSubscription?->plan?->name ?? 'Free' }}</span></p>
                            <p>{{ __('admin.user_management.status') }}: <span class="text-amber-200">{{ $user->activeSubscription?->status ? ucfirst($user->activeSubscription->status) : 'n/a' }}</span></p>
                            <p>{{ __('profile.last_login') }}: <span class="text-amber-200">{{ optional($user->last_login_at)->diffForHumans() ?? '—' }}</span></p>
                        </div>
                        <a href="{{ route('admin.users.show', $user) }}" class="rounded-full bg-slate-800 px-5 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-amber-200 hover:bg-slate-700">
                            {{ __('admin.user_management.details') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>
@endsection
