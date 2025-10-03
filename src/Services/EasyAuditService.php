<?php

namespace Jonasschen\LaravelEasyAudits\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jonasschen\LaravelEasyAudits\Models\EasyAudit;

final class EasyAuditService
{
    public function __construct() {}

    public function prune(int $ttl): int
    {
        if ($ttl <= 0) {
            return 0;
        }

        /** @var Model $model */
        $modelClass = config('easy-audits.model_class', EasyAudit::class);
        $model = new $modelClass;
        $tableName = $model->getTable();

        return DB::table($tableName)
            ->where('created_at', '<=', now()->subDays($ttl))
            ->delete();
    }
}
