<?php

namespace Jonasschen\LaravelEasyAudits;

use Jonasschen\LaravelEasyAudits\Models\EasyAudit;

class LaravelEasyAudits
{
    public function store(array $data): void
    {
        app(config('easy-audits.model_class', EasyAudit::class))->create($data);
    }
}
