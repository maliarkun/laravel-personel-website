<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->get('lang') ?? Session::get('app_locale', config('app.locale'));

        if (! in_array($locale, ['en', 'tr'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        Session::put('app_locale', $locale);

        return $next($request);
    }
}
