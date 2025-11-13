<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserPlanRequest;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use App\Support\UserActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with(['roles', 'activeSubscription.plan']);

        if ($role = $request->string('role')->toString()) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        }

        if ($plan = $request->string('plan')->toString()) {
            $query->whereHas('activeSubscription.plan', fn ($q) => $q->where('slug', $plan));
        }

        if ($status = $request->string('status')->toString()) {
            if ($status === 'cancelled') {
                $query->whereHas('subscriptions', fn ($q) => $q->where('status', 'cancelled'));
            } else {
                $query->whereHas('activeSubscription', fn ($q) => $q->where('status', $status));
            }
        }

        $users = $query->latest()->paginate(12)->withQueryString();
        $plans = SubscriptionPlan::all();

        return view('admin.users.index', compact('users', 'plans'));
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'subscriptions.plan', 'activityLogs' => fn ($query) => $query->latest()->limit(20)]);
        $plans = SubscriptionPlan::all();

        return view('admin.users.show', compact('user', 'plans'));
    }

    public function updateRoles(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        $user->syncRoles($request->validated('roles'));

        UserActivityLogger::log($user, 'roles_updated:' . implode(',', $request->validated('roles')), $request);

        return back()->with('status', __('admin.user_management.roles_updated'));
    }

    public function updatePlan(UpdateUserPlanRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $subscription = new UserSubscription([
            'plan_id' => $data['plan_id'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'] ?? null,
            'status' => $data['status'],
        ]);

        $user->subscriptions()->save($subscription);

        $subscription->load('plan');

        UserActivityLogger::log($user, 'subscription_updated:' . ($subscription->plan?->slug ?? 'none'), $request);

        return back()->with('status', __('admin.user_management.plan_updated'));
    }

    public function toggleLock(Request $request, User $user): RedirectResponse
    {
        $user->forceFill([
            'is_locked' => ! $user->is_locked,
        ])->save();

        $action = $user->is_locked ? 'account_locked' : 'account_unlocked';
        UserActivityLogger::log($user, $action, $request);

        return back()->with('status', __('admin.user_management.lock_toggled'));
    }
}
