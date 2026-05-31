<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->tableExistsButIsUnusable()) {
            Schema::dropIfExists('kia_layanan_pembiayaan');
        }

        if (! Schema::hasTable('kia_layanan_pembiayaan')) {
            Schema::create('kia_layanan_pembiayaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
                $table->string('puskesmas_domisili')->nullable();
                $table->string('no_reg_kohort_ibu')->nullable();
                $table->string('no_reg_kohort_bayi')->nullable();
                $table->string('no_reg_kohort_balita')->nullable();
                $table->string('no_catatan_medik_rs')->nullable();
                $table->string('asuransi_lain')->nullable();
                $table->string('no_asuransi_lain')->nullable();
                $table->date('tanggal_berlaku_asuransi_lain')->nullable();
                $table->string('asuransi_suami')->nullable();
                $table->string('no_asuransi_suami')->nullable();
                $table->date('tanggal_berlaku_asuransi_suami')->nullable();
                $table->string('puskesmas_domisili_suami')->nullable();
                $table->string('no_catatan_medik_rs_suami')->nullable();
                $table->string('asuransi_anak')->nullable();
                $table->string('no_asuransi_anak')->nullable();
                $table->date('tanggal_berlaku_asuransi_anak')->nullable();
                $table->string('puskesmas_domisili_anak')->nullable();
                $table->string('no_catatan_medik_rs_anak')->nullable();
                $table->timestamps();
            });

            return;
        }

        $columns = [
            'puskesmas_domisili' => 'string',
            'no_reg_kohort_ibu' => 'string',
            'no_reg_kohort_bayi' => 'string',
            'no_reg_kohort_balita' => 'string',
            'no_catatan_medik_rs' => 'string',
            'asuransi_lain' => 'string',
            'no_asuransi_lain' => 'string',
            'tanggal_berlaku_asuransi_lain' => 'date',
            'asuransi_suami' => 'string',
            'no_asuransi_suami' => 'string',
            'tanggal_berlaku_asuransi_suami' => 'date',
            'puskesmas_domisili_suami' => 'string',
            'no_catatan_medik_rs_suami' => 'string',
            'asuransi_anak' => 'string',
            'no_asuransi_anak' => 'string',
            'tanggal_berlaku_asuransi_anak' => 'date',
            'puskesmas_domisili_anak' => 'string',
            'no_catatan_medik_rs_anak' => 'string',
        ];

        foreach ($columns as $column => $type) {
            if (Schema::hasColumn('kia_layanan_pembiayaan', $column)) {
                continue;
            }

            Schema::table('kia_layanan_pembiayaan', function (Blueprint $table) use ($column, $type) {
                $type === 'date'
                    ? $table->date($column)->nullable()
                    : $table->string($column)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kia_layanan_pembiayaan');
    }

    private function tableExistsButIsUnusable(): bool
    {
        if (! Schema::hasTable('kia_layanan_pembiayaan')) {
            return false;
        }

        try {
            DB::select('SELECT 1 FROM kia_layanan_pembiayaan LIMIT 1');

            return false;
        } catch (QueryException) {
            return true;
        }
    }
};
