<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors(['login_error' => "Terlalu banyak percobaan masuk. Silakan coba lagi dalam {$seconds} detik."])
                ->onlyInput('email');
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey);

        $attempts = RateLimiter::attempts($throttleKey);
        $remaining = max(0, 3 - $attempts);

        $errorMessage = 'Email atau password yang Anda masukkan salah.';
        if ($remaining > 0) {
            $errorMessage .= " Sisa percobaan: {$remaining} kali.";
        } else {
            $errorMessage = 'Terlalu banyak percobaan salah. Akun Anda dikunci sementara.';
        }

        return back()
            ->withErrors(['login_error' => $errorMessage])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
}