<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordConfirmController extends Controller
{
    /**
     * Check if password is confirmed (returns JSON).
     */
    public function check(Request $request)
    {
        if ($request->user()) {
            return response()->json(['confirmed' => true]);
        }
        return response()->json(['confirmed' => false]);
    }

    /**
     * Confirm password with plain Hash::check.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.',
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            if (!$request->expectsJson()) {
                throw ValidationException::withMessages([
                    'password' => ['Password tidak valid.'],
                ]);
            }

            return response()->json([
                'message' => 'Password tidak valid.',
                'errors' => ['password' => ['Password tidak valid.']],
            ], 422);
        }

        // Mark as confirmed in session
        $request->session()->put('auth.password_confirmed_at', now()->unix());

        if (!$request->expectsJson()) {
            return back();
        }

        return response()->json([
            'message' => 'Password confirmed successfully.',
        ]);
    }
}
