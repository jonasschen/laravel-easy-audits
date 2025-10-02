<?php

namespace Jonasschen\LaravelEasyAudits\Providers;

use Illuminate\Support\ServiceProvider;
use Jonasschen\LaravelEasyAudits\Console\Commands\EasyAuditsPruneCommand;
use Jonasschen\LaravelEasyAudits\LaravelEasyAudits;

class EasyAuditsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $baseDir = __DIR__ . '/../../';
        $this->loadMigrationsFrom($baseDir . 'database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $baseDir . 'config/config.php' => config_path('easy-audits.php'),
            ], 'easy-audits-config');

            $this->publishes([
                $baseDir . 'database/migrations' => database_path('migrations'),
            ], 'easy-audits-migrations');

            // Registering package commands.
            $this->commands([
                EasyAuditsPruneCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $baseDir = __DIR__ . '/../../';

        $this->mergeConfigFrom($baseDir . 'config/config.php', 'easy-audits');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-easy-audits', function () {
            return new LaravelEasyAudits();
        });
    }
}
