<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }
        auth()->logout();
        return redirect()->route('auth.login')->withErrors([
            'login_error' => 'Silakan login sebagai administrator terlebih dahulu.'
        ]);
    }
}