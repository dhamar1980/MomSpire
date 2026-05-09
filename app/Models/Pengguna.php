<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function anak()
    {
        return $this->hasMany(Anak::class, 'pengguna_id')->orderBy('anak_ke');
    }
}
