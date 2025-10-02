<?php

namespace Jonasschen\LaravelEasyAudits\Console\Commands;

use Illuminate\Console\Command;
use Jonasschen\LaravelEasyAudits\Services\EasyAuditService;

final class EasyAuditsPruneCommand extends Command
{
    protected $signature = 'easy_audits:prune {--audits_ttl= : Quantity of days audit logs should remain in the database before being pruned.}';

    protected $description = 'Perform pruning of the audit logs from the database according to the number of days configured in the config file or the parameter provided in the command.';

    public function __construct(
        private readonly EasyAuditService $easyAuditService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->comment(str_repeat('*', 40));
        $this->comment('*  LARAVEL EASY AUDITS PRUNING REPORT  *');
        $this->comment(str_repeat('*', 40));

        $ttl = $this->option('audits_ttl') ?? null;
        if ($ttl) {
            $this->info("TTL loaded from option parameter: {$ttl} days");
        } else {
            $ttl = config('easy-audits.audits_ttl', 0);
            $this->info("TTL loaded from config file: {$ttl} days");
        }

        $startTime = microtime(true);
        $pruned = $this->easyAuditService->prune($ttl);
        $executionTime = number_format(microtime(true) - $startTime, 2);

        $this->info("Pruned [{$pruned}] records in [{$executionTime}] seconds.");

        return 0;
    }
}
