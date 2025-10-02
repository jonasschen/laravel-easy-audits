<?php

namespace Jonasschen\LaravelEasyAudits\Models;

use Illuminate\Database\Eloquent\Model;
use Jonasschen\LaravelEasyAudits\Enums\AuditEventTypeEnum;

final class EasyAudit extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'event_type',
        'table',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'event_type' => AuditEventTypeEnum::class,
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function getTable()
    {
        return config('easy-audits.audit_table_name', 'audits');
    }
}
