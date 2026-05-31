<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $verifiedAt = now();

        foreach (['users', 'pengguna', 'bidan', 'dokter', 'admin'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'email_verified_at')) {
                continue;
            }

            DB::table($table)
                ->whereNull('email_verified_at')
                ->update(['email_verified_at' => $verifiedAt]);
        }
    }

    public function down(): void
    {
        //
    }
};
