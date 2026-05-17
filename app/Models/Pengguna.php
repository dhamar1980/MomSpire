<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'no_telp',
        'password',
        'is_hamil',
        'profile_photo_path',
        'is_online',
        'last_seen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_seen' => 'datetime',
    ];

    /**
     * Update last_seen timestamp setiap kali pengguna aktif.
     * Set is_online = true juga untuk menunjukkan user sedang aktif.
     */
    public function updateLastSeen(): void
    {
        $this->is_online = true;
        $this->last_seen = now();
        $this->save();
    }

    public function anak(): HasMany
    {
        return $this->hasMany(Anak::class, 'pengguna_id')->orderBy('anak_ke');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'pengguna_id');
    }

    public function jadwalPemantauan(): HasMany
    {
        return $this->hasMany(JadwalPemantauan::class, 'pengguna_id');
    }
}
