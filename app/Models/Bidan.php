<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidan extends Model
{
    protected $table = 'bidan';
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
     * Update last_seen timestamp setiap kali bidan aktif.
     * Set is_online = true juga untuk menunjukkan user sedang aktif.
     */
    public function updateLastSeen(): void
    {
        $this->is_online = true;
        $this->last_seen = now();
        $this->save();
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'professional_id')->where('professional_type', 'bidan');
    }

    public function jadwalPemantauan(): HasMany
    {
        return $this->hasMany(JadwalPemantauan::class, 'bidan_id');
    }
}
