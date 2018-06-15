# Varnish integration for Laravel

The package inspired by [this laravel-varnish integration](https://github.com/spatie/laravel-varnish), but implements the idea of dynamic entity-related caching.

## Installation

#### Varnish configuration
First, you will need to install the Varnish on your server. To work with this extension, you need a special vcl configuration. Please see 
[VCL configuration templates](varnish-configuration-templates)

#### Laravel instalation
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



## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.