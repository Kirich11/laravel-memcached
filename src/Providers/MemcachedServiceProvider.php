<?php

namespace Kirich\LaravelMemcahced\Providers;

use Illuminate\Support\ServiceProvider;

class MemcachedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'../config/memcached.php' => config_path('memcached.php'),
        ]);
    }
}
