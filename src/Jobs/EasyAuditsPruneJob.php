<?php

namespace Jonasschen\LaravelEasyAudits\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Jonasschen\LaravelEasyAudits\Services\EasyAuditService;

final class EasyAuditsPruneJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function middleware(): array
    {
        return [new WithoutOverlapping('easy-audits-prune')];
    }

    public function handle(EasyAuditService $easyAuditService): void
    {
        info('Running Job...');
        $ttl = config('easy-audits.audits_ttl', 0);
        $easyAuditService->prune((int) $ttl);
    }
}
