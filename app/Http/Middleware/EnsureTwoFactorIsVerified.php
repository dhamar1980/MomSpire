<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || blank($user->two_factor_secret) || blank($user->two_factor_confirmed_at)) {
            return $next($request);
        }

        if ($request->session()->has('auth.two_factor_verified_at')) {
            return $next($request);
        }

        $request->session()->put('login.id', $user->getKey());
        $request->session()->put('login.remember', false);

        Auth::logout();
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Two factor authentication challenge required.',
                'redirect' => route('two-factor-challenge'),
            ], 423);
        }

        return redirect()->route('two-factor-challenge');
    }
}
