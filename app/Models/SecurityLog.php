<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    protected $fillable = [
        'event_type',
        'severity',
        'message',
        'context',
        'ip_address',
        'user_agent',
        'user_id',
        'username',
        'url',
        'method',
        'request_data',
        'alert_sent',
        'alert_sent_at',
    ];

    protected $casts = [
        'context' => 'array',
        'request_data' => 'array',
        'alert_sent' => 'boolean',
        'alert_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeHighRisk($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    public function scopeUnsentAlerts($query)
    {
        return $query->where('alert_sent', false)->highRisk();
    }
}
