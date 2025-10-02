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
}
