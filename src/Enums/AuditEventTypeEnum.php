<?php

namespace Jonasschen\LaravelEasyAudits\Enums;

use ReflectionClass;

enum AuditEventTypeEnum: string
{
    case DELETE = 'DELETE';
    case FORCE_DELETE = 'FORCE_DELETE';
    case INSERT = 'INSERT';
    case RESTORE = 'RESTORE';
    case SOFT_DELETE = 'SOFT_DELETE';
    case UPDATE = 'UPDATE';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }
}
