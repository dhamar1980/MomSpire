<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class TemplateBukuKIA extends Model
{
    protected $table = 'template_buku_kia';

    protected $fillable = [
        'nama',
        'deskripsi',
        'file_path',
        'uploaded_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public static function defaultPdfPath(): string
    {
        return resource_path('views/buku/Buku KIA (Permenkes).pdf');
    }

    public static function latestAvailable(): ?self
    {
        if (! Schema::hasTable('template_buku_kia')) {
            return null;
        }

        return static::query()
            ->latest()
            ->get()
            ->first(fn (self $template) => Storage::disk('public')->exists($template->file_path));
    }

    public static function resolvePdfFile(): array
    {
        $template = static::latestAvailable();

        if ($template) {
            $name = trim((string) ($template->nama ?: 'Buku KIA Template'));
            $filename = preg_match('/\.pdf$/i', $name) ? $name : $name . '.pdf';

            return [
                Storage::disk('public')->path($template->file_path),
                str_replace('"', '', $filename),
                $template,
            ];
        }

        return [
            static::defaultPdfPath(),
            'Buku KIA (Permenkes).pdf',
            null,
        ];
    }
}
