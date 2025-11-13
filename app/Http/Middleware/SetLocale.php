<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // URL parametresi -> session -> varsayılan app.locale
        $locale = $request->get('lang', session('locale', config('app.locale')));

        // Sadece tr ve en'ye izin ver, diğerlerini varsayılan dile çek
        if (! in_array($locale, ['tr', 'en'], true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        session()->put('locale', $locale);

        return $next($request);
    }
}