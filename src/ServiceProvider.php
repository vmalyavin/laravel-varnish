<?php

namespace Minter\Varnish;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Minter\Varnish\Commands\FlushVarnishCache;
use Minter\Varnish\Service\CacheManagementDummyService;
use Minter\Varnish\Service\CacheManagementService;
use Minter\Varnish\Service\CacheManagementServiceInterface;
use Minter\Varnish\Service\CacheResponseService;
use Minter\Varnish\Service\CacheResponseServiceInterface;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Publishing config
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('varnish.php')], 'config');
        }
    }

    /**
     * Register the service provider and merge config.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'varnish');

        // cache management service di
        $this->app->singleton(CacheManagementServiceInterface::class, function ($app) {
            $options = $app['config']->get('varnish');

            // if varnish is not configured properly on needed url - dummy service
            if (!$options['is_configured']) {
                return new CacheManagementDummyService();
            }

            return new CacheManagementService(new Client(
                ['base_uri' => $options['management_uri'], 'timeout' => $options['management_timeout']]
            ));
        });
        // cache response service di
        $this->app->singleton(CacheResponseServiceInterface::class, CacheResponseService::class);

        // add command to clear cache
        $this->commands([FlushVarnishCache::class]);
    }

    /**
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../config/varnish.php';
    }
}