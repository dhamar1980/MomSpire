<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anak extends Model
{
    protected $table = 'anak';

    protected $fillable = [
        'pengguna_id',
        'anak_ke',
        'nama_anak',
        'tanggal_lahir',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
