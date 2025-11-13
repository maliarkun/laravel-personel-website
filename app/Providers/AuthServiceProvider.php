<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Only admins can fully manage content
        Gate::define('manage-content', function (User $user): bool {
            return $user->hasRole('admin');
        });

        // Admins + editors can edit content
        Gate::define('edit-content', function (User $user): bool {
            return $user->hasAnyRole(['admin', 'editor']);
        });

        // Super-admin override: this user can do everything
        Gate::before(function (User $user, string $ability) {
            if ($user->email === 'maliarkun@arkun.net') {
                return true; // allow all abilities for this user
            }

            return null; // continue with normal gate checks for others
        });
    }
}
