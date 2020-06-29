<?php

namespace Kirich\LaravelMemcached\Providers;

use Illuminate\Support\ServiceProvider;
use Kirich\LaravelMemcached\Services\Cache\MemcachedService;

class MemcachedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MemcachedService', function ($app) {
            return new MemcachedService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/memcached.php' => config_path('memcached.php'),
        ]);
    }
}
