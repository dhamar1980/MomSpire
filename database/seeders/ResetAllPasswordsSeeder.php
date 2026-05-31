<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetAllPasswordsSeeder extends Seeder
{
    /**
     * Reset semua password user berdasarkan role.
     *
     * Format password: [role]123
     * Contoh: pengguna123, bidan123, dokter123, admin123
     *
     * Run: php artisan db:seed --class=ResetAllPasswordsSeeder
     */
    public function run(): void
    {
        $passwords = [
            'pengguna' => 'pengguna123',
            'bidan'     => 'bidan123',
            'dokter'    => 'dokter123',
            'admin'     => 'admin123',
        ];

        // Reset password di tabel users (central)
        foreach ($passwords as $role => $password) {
            $hashedPassword = Hash::make($password);

            // Update di tabel users
            DB::table('users')
                ->where('role', $role)
                ->update(['password' => $hashedPassword]);

            // Update juga di tabel role-specific (kecuali admin)
            if ($role !== 'admin' && DB::table($role)->exists()) {
                DB::table($role)
                    ->update(['password' => $hashedPassword]);
            }

            $count = DB::table('users')->where('role', $role)->count();
            echo "✓ Reset {$count} user dengan role '{$role}' → password: {$password}" . PHP_EOL;
        }

        echo PHP_EOL;
        echo '=== Password Default Setelah Reset ===' . PHP_EOL;
        echo 'pengguna  → pengguna123' . PHP_EOL;
        echo 'bidan     → bidan123' . PHP_EOL;
        echo 'dokter    → dokter123' . PHP_EOL;
        echo 'admin     → admin123' . PHP_EOL;
        echo PHP_EOL;
    }
}