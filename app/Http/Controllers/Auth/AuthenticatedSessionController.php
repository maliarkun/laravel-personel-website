<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()->is_locked) {
            Auth::logout();
            $request->session()->invalidate(); // Kilitli hesap giriş denemesinde oturum izlerini temizliyoruz.
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => __('auth.account_locked'),
            ])->onlyInput('email'); // Kilitli hesaplar için ek güvenlik kontrolü.
        }

        return redirect()->intended(route('account.profile.show')); // Giriş sonrası kullanıcı hesap alanına yönlendiriyoruz.
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
