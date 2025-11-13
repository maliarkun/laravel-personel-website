<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage-content', fn (User $user): bool => $user->hasRole('admin'));

        Gate::define('edit-content', fn (User $user): bool => $user->hasAnyRole(['admin', 'editor']));

        Gate::before(function ($user, $ability) {
            if ($user->email === 'maliarkun@arkun.net') { 
            }
    }
}
