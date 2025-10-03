<?php

use Jonasschen\LaravelEasyAudits\Models\EasyAudit;
use Jonasschen\LaravelEasyAudits\Observers\EasyAuditsObserver;

return [
    /*
    |--------------------------------------------------------------------------
    | Audit table name
    |--------------------------------------------------------------------------
    |
    | Table name where audit logs will be stored.
    | NOTE: After the migrate process has been executed, changing this
    | configuration will break the package.
    |
    */
    'audit_table_name' => 'audits',

    /*
    |--------------------------------------------------------------------------
    | Enabled Triggers
    |--------------------------------------------------------------------------
    |
    | You can customize which types of changes you want to audit.
    | This setting is global and affects all enabled models/entities in the
    | project, unless this setting is overridden via the model/entity's
    | $enabledTriggers property.
    |
    */
    'enabled_triggers' => [
        'insert',
        'update',
        'delete',
        'soft_delete',
        'force_delete',
        'restore',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model class
    |--------------------------------------------------------------------------
    |
    | Model class to be used to persist in the database.
    | You can change this configuration if you want to override the model class.
    |
    */
    'model_class' => EasyAudit::class,

    /*
    |--------------------------------------------------------------------------
    | Observer class
    |--------------------------------------------------------------------------
    |
    | Observer class to be used to persist in the database
    | You can change this configuration if you want to override the observer class.
    |
    */
    'observer_class' => EasyAuditsObserver::class,

    /*
    |--------------------------------------------------------------------------
    | Audits time-to-life (in days)
    |--------------------------------------------------------------------------
    |
    | Quantity of days audit logs should remain in the database before being pruned.
    | Zero means no pruning.
    |
    */
    'audits_ttl' => 0,
];
