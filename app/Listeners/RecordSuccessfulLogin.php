<?php

namespace App\Listeners;

use App\Support\UserActivityLogger;
use Illuminate\Auth\Events\Login;

class RecordSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        $request = $event->request;

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request?->ip(),
        ])->save();

        UserActivityLogger::log($user, 'login', $request);
    }
}
