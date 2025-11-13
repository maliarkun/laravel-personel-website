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

        Gate::define('manage-content', function (User $user): bool {
            return $user->hasRole('admin'); // Yeni rol tablosu ile tam yetkiyi adminlere veriyoruz.
        });

        Gate::define('edit-content', function (User $user): bool {
            return $user->hasAnyRole(['admin', 'editor']); // İçerik düzenleme izni admin ve editörlerle sınırlandı.
        });

        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin') && $user->email === 'maliarkun@arkun.net') {
                return true; // Varsayılan admin hesabı için tüm yetkileri saklı tuttuk.
            }

            return null;
        });
    }
}
