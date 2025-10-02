<?php

namespace Jonasschen\LaravelEasyAudits\Enums;

use ReflectionClass;

enum AuditEventTypeEnum: string
{
    case INSERT = 'INSERT';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
    case FORCE_DELETE = 'FORCE_DELETE';
    case RESTORE = 'RESTORE';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }
}
