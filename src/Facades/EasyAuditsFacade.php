<?php

namespace Jonasschen\LaravelEasyAudits\Facades;

use Illuminate\Support\Facades\Facade;

class EasyAuditsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-easy-audits';
    }
}
