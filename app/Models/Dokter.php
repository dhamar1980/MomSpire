<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Update last_seen timestamp setiap kali dokter aktif.
     * Set is_online = true juga untuk menunjukkan user sedang aktif.
     */
    public function updateLastSeen(): void
    {
        $this->is_online = true;
        $this->last_seen = now();
        $this->save();
    }
}
