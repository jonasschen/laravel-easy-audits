<p align="center">
    <img src="assets/logo_easy_audits.jpg" alt="Logo"/>
</p>

# Laravel Easy Audits

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jonasschen/laravel-easy-audits.svg?style=flat-square)](https://packagist.org/packages/jonasschen/laravel-easy-audits)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/jonasschen/laravel-easy-audits.svg?style=flat-square)](https://packagist.org/packages/jonasschen/laravel-easy-audits)
![GitHub Actions](https://github.com/jonasschen/laravel-easy-audits/actions/workflows/main.yml/badge.svg)

A quick and easy way to audit changes to your Laravel project's database.

Using Laravel Easy Audits, you can get all missing translations.
## Installation

- You can install the package via composer:
```bash
composer require jonasschen/laravel-easy-audits
```

- Publish the config files using the artisan CLI tool:
```bash
php artisan vendor:publish --tag=easy-audits-config
```
This command will publish the config file: `config/easy-audits.php`

- (OPTIONAL) Publish the migrations files using the artisan CLI tool:

If you want to customize the migration file, you can publish it within your project.

BUT BE CAREFUL, it is at your own risk; changes to the table structure may cause this package break.
```bash
php artisan vendor:publish --tag=easy-audits-migrations
```
This command will publish the migration file: `database/migrations/2025_10_01_000000_create_audits_table.php`

### Pruning logs
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
    /*** Other possible implementations here ***/
    
    $this->callAfterResolving(Schedule::class, fn (Schedule $schedule) =>
        $schedule->job(new EasyAuditsPruneJob())->dailyAt('01:00')
    );
}
```
NOTE: In the schedule settings, you are free to use any scheduling frequency supported by your Laravel version.

### Available configurations
- audit_table_name (Default: 'audits')
    - Audit table name: Table name where audit logs will be stored. NOTE: After the migrate process has been executed, changing this configuration will break the package. 
- model_class (Default: EasyAudit::class)
    - Model class: Model class to be used to persist in the database. You can change this configuration if you want to override the model class. 
- observer_class (Default: EasyAuditsObserver::class)
    - Observer class: Observer class to be used to persist in the database. You can change this configuration if you want to override the observer class.
- audits_ttl (Default: 0)
    - Observer class: Quantity of days audit logs should remain in the database before being pruned. Zero means no pruning.

## Consider Sponsoring
Help me maintain this project, please consider looking at the [FUNDING](./.github/FUNDING.yml) file for more info.

<a href="https://bmc.link/jonasschen" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>

#### BTC
![btc](https://github.com/jonasschen/laravel-easy-audits/assets/31046817/2f69a4aa-4ee2-442e-aa1f-4a1c0cde217c)

#### ETH
![eth](https://github.com/jonasschen/laravel-easy-audits/assets/31046817/41ca0d2f-e120-4733-a96b-ff7a34e1e4de)

### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

### Testing
```bash
composer test
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security
If you discover any security-related issues, please email jonasschen@gmail.com instead of using the issue tracker. Please do not email any questions, open an issue if you have a question.

## Credits
-   [Jonas Schen](https://github.com/jonasschen)
-   [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
