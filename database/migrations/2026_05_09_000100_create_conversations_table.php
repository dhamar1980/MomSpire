<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->enum('professional_type', ['bidan', 'dokter']);
            $table->foreignId('professional_id')->nullable();
            $table->text('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->unique(['pengguna_id', 'professional_type', 'professional_id'], 'conv_unique_idx');
            $table->index(['pengguna_id', 'professional_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
