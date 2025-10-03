<?php

namespace Jonasschen\LaravelEasyAudits\Traits;

use Illuminate\Database\Eloquent\Model;
use Jonasschen\LaravelEasyAudits\Observers\EasyAuditsObserver;

/**
 * @mixin Model
 */
trait EasyAuditsTrait
{
    protected static bool $easyAuditsObserverRegistered = false;

    public static function bootEasyAuditsTrait(): void
    {
        if (!isset(static::$easyAuditsObserverRegistered) || !static::$easyAuditsObserverRegistered) {
            static::observe(config('easy-audits.observer_class', EasyAuditsObserver::class));
            static::$easyAuditsObserverRegistered = true;
        }
    }

    public function getAuditableAttributes(): array
    {
        return $this->auditableAttributes ?? [
            '*',
        ];
    }

    public function getNonAuditableAttributes(): array
    {
        return $this->nonAuditableAttributes ?? [];
    }

    public function getEnabledTriggers(): array
    {
        return $this->enabledTriggers ?? config('easy-audits.enabled_triggers', [
            'insert',
            'update',
            'delete',
            'soft_delete',
            'force_delete',
            'restore',
        ]);
    }
}
