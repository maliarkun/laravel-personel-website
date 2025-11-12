<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('manage-content', fn ($user) => $user->hasRole('admin'));
        Gate::define('edit-content', fn ($user) => $user->hasAnyRole(['admin', 'editor']));
    }
}
