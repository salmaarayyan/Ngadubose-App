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
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }
        auth()->logout();
        return redirect('/admin/login')->withErrors([
            'login_error' => 'Silakan login sebagai administrator terlebih dahulu.'
        ]);
    }
}