<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $timeoutSeconds = 10800): Response
    {
        if (!$this->passwordIsConfirmed($request, $timeoutSeconds)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Password confirmation required.'], 403);
            }

            return app(\Laravel\Fortify\Http\Controllers\ConfirmablePasswordController::class)->show($request);
        }

        return $next($request);
    }

    /**
     * Determine if the user's password has been recently confirmed.
     */
    protected function passwordIsConfirmed(Request $request, int $timeoutSeconds): bool
    {
        $timestamp = $request->session()->get('auth.password_confirmed_at', 0);

        if ($timestamp === 0) {
            return false;
        }

        return (time() - $timestamp) < $timeoutSeconds;
    }
}