<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKIA extends Model
{
    use HasFactory;

    protected $table = 'buku_kia';

    protected $fillable = [
        'pengguna_id',
        'created_by',
        'judul',
        'catatan',
        'file_path',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
