<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['users', 'pengguna'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'usia_kehamilan_minggu')) {
                    $column = Schema::hasColumn($tableName, 'no_telp') ? 'no_telp' : 'password';
                    $table->unsignedTinyInteger('usia_kehamilan_minggu')->nullable()->after($column);
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['pengguna', 'users'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'usia_kehamilan_minggu')) {
                    $table->dropColumn('usia_kehamilan_minggu');
                }
            });
        }
    }
};