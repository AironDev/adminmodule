<?php

namespace Modules\Admin\Providers;

use Arrilot\Widgets\ServiceProvider as WidgetServiceProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Seed;

class AdminModuleDummyServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(WidgetServiceProvider::class);

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
        }
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/../publishable';

        $publishable = [
            'dummy_seeders' => [
                "{$publishablePath}/database/dummy_seeders/" => database_path('seeders'),
            ],
            'dummy_content' => [
                "{$publishablePath}/dummy_content/" => storage_path('app/public'),
            ],
            'dummy_config' => [
                "{$publishablePath}/config/voyager_dummy.php" => config_path('voyager.php'),
            ],
            'dummy_migrations' => [
                "{$publishablePath}/database/migrations/" => database_path('migrations'),
            ],

        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/../publishable/config/voyager_dummy.php',
            'voyager'
        );
    }
}
