<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            \App\Listeners\RecordSuccessfulLogin::class,
        ],
        Logout::class => [
            \App\Listeners\RecordUserLogout::class,
        ],
    ]; // Giriş/çıkış aktivitelerini dinleyerek log ve son giriş bilgilerimizi güncelliyoruz.

    public function boot(): void
    {
        parent::boot();
    }
}
