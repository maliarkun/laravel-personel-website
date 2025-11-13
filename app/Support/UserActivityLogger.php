<?php

namespace App\Support;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityLogger
{
    public static function log(User $user, string $action, ?Request $request = null): void
    {
        UserActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'ip' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
