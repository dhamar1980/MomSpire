<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the created_at attribute as WIB (Asia/Jakarta) string.
     * Ini memastikan timestamp selalu dalam format WIB, tidak peduli timezone MySQL server.
     */
    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) return null;

        // Parse sebagai UTC, lalu konversi ke Asia/Jakarta
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}
