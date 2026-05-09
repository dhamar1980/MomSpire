<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtikelEdukasi extends Model
{
    protected $table = 'artikel_edukasi';

    protected $fillable = [
        'title',
        'category',
        'image_url',
        'summary',
        'article_url',
        'min_week',
        'max_week',
    ];

    protected $casts = [
        'min_week' => 'integer',
        'max_week' => 'integer',
    ];
}