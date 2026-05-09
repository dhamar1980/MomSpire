<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel_edukasi', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 50)->default('umum');
            $table->string('image_url')->nullable();
            $table->text('summary');
            $table->string('article_url')->nullable();
            $table->unsignedSmallInteger('min_week')->nullable();
            $table->unsignedSmallInteger('max_week')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel_edukasi');
    }
};