<?php

namespace App\Listeners;

use App\Support\UserActivityLogger;
use Illuminate\Auth\Events\Logout;

class RecordUserLogout
{
    public function handle(Logout $event): void
    {
        if ($event->user) {
            $request = request(); // ← Doğru şekilde request alıyoruz

            UserActivityLogger::log($event->user, 'logout', $request);
        }
    }
}