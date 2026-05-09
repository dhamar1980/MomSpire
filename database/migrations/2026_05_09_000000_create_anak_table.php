<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->integer('anak_ke')->default(1);
            $table->string('nama_anak')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('status', ['dalam_kandungan', 'lahir'])->default('lahir');
            $table->timestamps();

            $table->unique(['pengguna_id', 'anak_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anak');
    }
};
