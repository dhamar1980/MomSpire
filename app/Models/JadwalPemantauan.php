<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPemantauan extends Model
{
    protected $table = 'jadwal_pemantauan';

    protected $fillable = [
        'pengguna_id',
        'bidan_id',
        'dokter_id',
        'jenis',
        'judul',
        'tanggal',
        'waktu',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function bidan(): BelongsTo
    {
        return $this->belongsTo(Bidan::class, 'bidan_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
}