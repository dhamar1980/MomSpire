<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * Update last_seen timestamp setiap kali pengguna aktif di web.
     * Ini digunakan untuk menampilkan status online di halaman konsultasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $role = $user->role ?? null;
            $userId = $user->id;

            if ($role === 'bidan') {
                $bidan = \App\Models\Bidan::find($userId);
                if ($bidan && method_exists($bidan, 'updateLastSeen')) {
                    $bidan->updateLastSeen();
                }
            } elseif ($role === 'dokter') {
                $dokter = \App\Models\Dokter::find($userId);
                if ($dokter && method_exists($dokter, 'updateLastSeen')) {
                    $dokter->updateLastSeen();
                }
            } elseif ($role === 'pengguna') {
                $pengguna = \App\Models\Pengguna::where('user_id', $userId)->first();
                if ($pengguna && method_exists($pengguna, 'updateLastSeen')) {
                    $pengguna->updateLastSeen();
                }
            }
        }

        return $next($request);
    }
}