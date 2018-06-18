# Varnish integration for Laravel API

The package inspired by [this laravel-varnish integration](https://github.com/spatie/laravel-varnish), but implements the idea of caching dynamic entity-related data in API responses(that means responses isn't need cookies)

## Installation

#### Varnish configuration
First, you will need to install the Varnish on your server. To work with this extension, you need a special vcl configuration. Please see 
[VCL configuration templates](varnish-configuration-templates)

#### Laravel installation
Install the package via composer:
``` bash
composer require MinterTeam/laravel-varnish
```
For laravel >=5.5 you don't need to add service provider to config. This package supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

To publish config file you need run command:
```bash
php artisan vendor:publish --provider="Minter\Varnish\ServiceProvider" --tag="config"
```
In the published `varnish.php` config file you need to set the `management_uri` to the uri where you have a Varnish, that listen PURGE/BAN requests

Next you need to add middleware:
```php
// app/Http/Kernel.php

protected $routeMiddleware = [
...
   'varnishcache' => \Minter\Varnish\Middleware\AddCacheHeaders::class,
];
```
Then you can use it.

## Usage

### Caching responses

Routes whose response must be cached must use `varnishcache` middleware:
```php
// your routes defenition

// Route GET /contact/info will be cached on default cache time from config(`varnish.default_cache_ttl`) 
Route::group(['middleware' => ['varnishcache']],function () {
    Route::get('/contact/info', ContactController::class . '@getInfo');
});
```

You can also specify cache TTL for route as middleware param, by using string values(`15min`,`30min`,`hour`,`day`,`week`,`month`,`year`) or integer value of seconds
```php
// your routes defenition

// Route GET /contact/info-that-never-change will be cached for year
Route::group(['middleware' => ['varnishcache:year']],function () {
    Route::get('/contact/info-that-never-change', ContactController::class . '@getInfoThatNeverChange');
});
// Route GET /contact/info-that-changes-every-5-minutes will be cached for 5 minutes
Route::group(['middleware' => ['varnishcache:300']],function () {
    Route::get('/contact/info-that-changes-every-5-minutes', ContactController::class . '@getInfoThatChangesEvery5Minutes');
});
```
### Tag cache related to special entity data

You can mark any response with special tags(to clear cache only related to entity, when entity data changes). To tag responses use `Minter\Varnish\Service\CacheResponseService`

```php 
// your routes defenition

Route::group(['middleware' => ['varnishcache:day']],function () {
    Route::get('/user/profile/{userId}', UserProfileController::class . '@getProfile');
});
```
```php 
// user profile controller 
...
use Minter\Varnish\Service\CacheResponseServiceInterface;
... 
    public function getProfile(int $userId)
    { 
        // Resolve CacheResponseService from Ioc Container by CacheResponseServiceInterface
        $cacheResponseService = $app->make(CacheResponseServiceInterface::class);
        // tag response with $userId, using addTag method
        $this->cacheResponseService->addTag("user:profile:{$userId}");
        
        return 'User ' . $userId;
    }
...
```
You also can use [Automatic Injection](https://laravel.com/docs/5.6/container#automatic-injection) to inject `CacheResponseService` or `CacheManagementService`

### Clear cache by tag

Package using HTTP requests to Varnish configured as described in [VCL configuration templates](varnish-configuration-templates)
If you aren't configured Varnish to serve cache clearing requests, or not configured `management_uri` param in config/varnish.php - you will have an error.

To clear cache by tag use `Minter\Varnish\Service\CacheManagmentService`
```php 
// user profile changed somewhere
...
use Minter\Varnish\Service\CacheManagmentServiceInterface;
...
        // Resolve CacheManagementService from Ioc Container by CacheManagmentServiceInterface
        $cacheManagementService = $app->make(CacheManagmentServiceInterface::class);
        // clear cache by array of tags
        $cacheManagementService->clearByTags(["user:profile:{$userId}"]);
    }
}
```
Use `Minter\Varnish\Service\CacheManagmentService::clearAll` method to clear all Varnish cache

### Clear varnish cache command

You always can clear app cache in varnish by call `varnish:flush` artisan command

```bash
php artisan varnish:flush
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.