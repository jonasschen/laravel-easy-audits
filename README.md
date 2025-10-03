<p align="center">
    <img src="assets/logo_easy_audits.jpg" alt="Logo"/>
</p>

# Laravel Easy Audits

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jonasschen/laravel-easy-audits.svg?style=flat-square)](https://packagist.org/packages/jonasschen/laravel-easy-audits)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/jonasschen/laravel-easy-audits.svg?style=flat-square)](https://packagist.org/packages/jonasschen/laravel-easy-audits)

A quick and easy way to audit changes to your Laravel project's database.

Using Laravel Easy Audits, you can get all missing translations.
## Installation

### You can install the package via composer:
```bash
composer require jonasschen/laravel-easy-audits
```

### Publish the config files using the artisan CLI tool:
```bash
php artisan vendor:publish --tag=easy-audits-config
```
This command will publish the config file: `config/easy-audits.php`

**Available configurations:**
- audit_table_name [Audit table name]: Table name where audit logs will be stored. NOTE: After the migrate process has been executed, changing this configuration will break the package.
    - Default Value:
    ```php
    'audit_table_name' => 'audits',
    ````
- enabled_triggers [Enabled Triggers]: You can customize which types of changes you want to audit. This setting is global and affects all enabled models/entities in the project, unless this setting is overridden via the model/entity's $enabledTriggers property.
    - Default Value:
    ```php 
    'enabled_triggers' => [
        'insert',
        'update',
        'delete',
        'soft_delete',
        'force_delete',
        'restore',
    ],
    ````
- model_class [Model class]: Model class to be used to persist in the database. You can change this configuration if you want to override the model class.
    - Default Value:
    ```php
    'model_class' => EasyAudit::class,
    ````
- observer_class [Observer class]: Observer class to be used to persist in the database. You can change this configuration if you want to override the observer class.
    - Default Value:
    ```php
    'observer_class' => EasyAuditsObserver::class,
    ````
- audits_ttl [Audits time-to-life (in days)]: Quantity of **days** audit logs should remain in the database before being pruned. Zero means no pruning.
    - Default Value:
    ```php
    'audits_ttl' => 0,
    ````

### Publish the migration files using the artisan CLI tool ( (OPTIONAL):

If you want to customize the migration file, you can publish it within your project.

BUT BE CAREFUL, it is at your own risk; changes to the table structure may cause this package break.
```bash
php artisan vendor:publish --tag=easy-audits-migrations
```
This command will publish the migration file: `database/migrations/2025_10_01_000000_create_audits_table.php`

## Usage
To enable auditing for a table, you only need to declare the `EasyAuditsTrait` trait in your model/entity.
```php
use Jonasschen\LaravelEasyAudits\Traits\EasyAuditsTrait;

class User extends Model
{
    use EasyAuditsTrait;
}
```
Ready! Now, every time you make a change to your model/entity, a new record will be created in the audit table.

This package is compatible with SoftDeletes and is able to audit the following types of changes: `insert`, `update`, `delete`, `soft_delete`, `force_delete` and `restore`.

### Available properties
The following properties are available to customize how auditing is performed on each model/entity:
- `$auditableAttributes`: Use this property when you want to audit only changes to specific fields. By default, all fields will always be audited.
```php
protected array $auditableAttributes = [
    'name',
    'email',
]; 
```
- `$nonAuditableAttributes`: Use this property when you want to remove specific fields from the audit. By default, all fields will always be audited.
```php
protected array $nonAuditableAttributes = [
    'password',
    'remember_token',
    'updated_at',
]; 
```
- `$disableTriggers`: Use this property when you want to disable auditing for a specific change type. Accepted values are: `insert`, `update`, `delete`, `soft_delete`, `force_delete` and `restore`. By default, all change types are audited.
```php
protected array $disableTriggers = [
    'restore',
    'soft_delete',    
]; 
```

## Pruning logs
It is possible to prune audited logs. However, before doing so, it is important to review the `audits_ttl` setting in your `config/easy-audits.php` file.

This setting set the package how many days logs should remain in the database before being deleted.

If you want to keep the data indefinitely, you must enter a value of 0 (zero) in the configuration.

### There are two ways to perform log pruning:
1) **FIRST WAY TO PRUNE LOGS**: Using the artisan command

  Use the command below.
```php
php artisan easy_audits:prune
```
#### Output example without missing translations
```
****************************************
*  LARAVEL EASY AUDITS PRUNING REPORT  *
****************************************
TTL loaded from config file: 14 days
Pruned [800] records in [0.22] seconds.
```

You can also use the `--audits_ttl` parameter to specify a custom TTL and ignore the configuration file:

Use the command below.
```php
php artisan easy_audits:prune --audits_ttl=30
```
#### Output example without missing translations
```
****************************************
*  LARAVEL EASY AUDITS PRUNING REPORT  *
****************************************
TTL loaded from option parameter: 30 days
Pruned [1400] records in [0.28] seconds.
```

2) **SECOND WAY TO PRUNE LOGS**: Using Schedule

If you find it useful, you can also set up a schedule to automatically run the pruning at your preferred time. 

To do this, simply add the following code to your project's `AppServiceProvider` class, which is usually located at `app/Providers/AppServiceProvider.php`
```PHP
use Jonasschen\LaravelEasyAudits\Jobs\EasyAuditsPruneJob;
use Illuminate\Console\Scheduling\Schedule;


public function boot(): void
{
    /*** Other implementations here ***/
    
    $this->callAfterResolving(Schedule::class, fn (Schedule $schedule) =>
        $schedule->job(new EasyAuditsPruneJob())->dailyAt('01:00')
    );
}
```
NOTE: In the schedule settings, you are free to use any scheduling frequency supported by your Laravel version. E.g. `->everyThirtyMinutes()`, `->hourly()`, `->daily()`, `->weekly()`, `->monthly()`, `->quarterly()`, `->yearly()`, etc.

## Troubleshooting
- If you get an error when running the migration or prune commands, please check if the all configurations are set to the correct value.
- If you get an error when running the migration, please check if the audit table already exists.
- If you get an error when running any command, please make sure you have the required Laravel PHP extensions installed in your PHP CLI. 

## Consider Sponsoring
Help me maintain this project, please consider looking at the [FUNDING](./.github/FUNDING.yml) file for more info.

<a href="https://bmc.link/jonasschen" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>

### BTC
![btc](https://github.com/jonasschen/laravel-easy-audits/assets/31046817/2f69a4aa-4ee2-442e-aa1f-4a1c0cde217c)

### ETH
![eth](https://github.com/jonasschen/laravel-easy-audits/assets/31046817/41ca0d2f-e120-4733-a96b-ff7a34e1e4de)

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security-related issues, please email jonasschen@gmail.com instead of using the issue tracker. Please do not email any questions, open an issue if you have a question.

## Credits
-   [Jonas Schen](https://github.com/jonasschen)
-   [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
