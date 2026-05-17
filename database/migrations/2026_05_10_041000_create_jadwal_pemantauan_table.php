<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pemantauan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('bidan_id')->nullable()->constrained('bidan')->nullOnDelete();
            $table->enum('jenis', ['kontrol', 'imunisasi']);
            $table->string('judul');
            $table->date('tanggal');
            $table->time('waktu')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->timestamps();

            $table->index(['pengguna_id', 'tanggal']);
            $table->index(['jenis', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pemantauan');
    }
};