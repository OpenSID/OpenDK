<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKeyAuditLog extends Model
{
    protected $table = 'api_key_audit_logs';

    protected $fillable = [
        'api_key_id',
        'user_id',
        'action',
        'payload',
        'ip_address',
        'user_agent',
        'success',
    ];

    protected $casts = [
        'payload' => 'array',
        'success' => 'boolean',
    ];

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
