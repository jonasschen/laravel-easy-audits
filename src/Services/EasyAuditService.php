<?php

namespace Jonasschen\LaravelEasyAudits\Services;

use Illuminate\Database\Eloquent\Model;

final class EasyAuditService
{
    public function __construct() {}

    public function prune(int $ttl): int
    {
        info('Running Service...');
        if ($ttl <= 0) {
            return 0;
        }

        /** @var Model $model */
        $model = config('easy-audits.model_class');

        return $model::query()
            ->where('created_at', '<=', now()->subDays($ttl))
            ->delete();
    }
}
