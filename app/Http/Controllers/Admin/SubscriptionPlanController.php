<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionPlanRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::latest()->paginate(10);

        return view('admin.subscriptions.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.subscriptions.plans.create');
    }

    public function store(SubscriptionPlanRequest $request): RedirectResponse
    {
        SubscriptionPlan::create($request->validated());

        return redirect()->route('admin.subscriptions.plans.index')->with('status', __('admin.plans.created'));
    }

    public function edit(SubscriptionPlan $plan): View
    {
        return view('admin.subscriptions.plans.edit', compact('plan'));
    }

    public function update(SubscriptionPlanRequest $request, SubscriptionPlan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return redirect()->route('admin.subscriptions.plans.index')->with('status', __('admin.plans.updated'));
    }

    public function destroy(SubscriptionPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('admin.subscriptions.plans.index')->with('status', __('admin.plans.deleted'));
    }
}
