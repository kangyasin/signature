<?php

namespace Kangyasin\Signature;

use Kangyasin\Signature\SignatureManager;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap.
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * setupConfig.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/config.php');

        if ($this->app instanceof LaravelApplication) {
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $source => config_path('signature.php'),
                ]);
            }
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('signature');
        }

        $this->mergeConfigFrom($source, 'signature');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(SignatureManager::class, function ($app) {
            return new SignatureManager($app);
        });

        $this->app->alias(SignatureManager::class, 'signature');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function providers()
    {
        return ['signature', SignatureManager::class];
    }
}
