# laravel-memcached

Package for Laravel for caching responses

## Installation

1. Add package to composer.json as a VCS repository
2. Install with `composer require kirich/laravelmemcached`
3. Add service provider to config/app.php 
        
  ```php
  /*
   * Package Service Providers...
   */
  \Kirich\LaravelMemcached\Providers\MemcachedServiceProvider::class,
  ```
4. Run `php artisan vendor:publish` for config
5. Add middleware to routes that you want to cache
  ```php
  use Kirich\LaravelMemcached\Middleware\CacheViews;

  Route::middleware(CacheViews::class)->group(function() {
    Route::get('cached-view', 'Controller@cached');
  });
  ```
6. Optionally add .env values (look in config/memcached.php) for memcached servers (there has to be 2 instances of memcached: primary & backup)

If you're using Homestead, then for default settings run manually memcached with 
```bash
memcached -p 11211 -d
memcached -p 11212 -d
```
