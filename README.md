# laravel-varnish

## Installation

Install the package via composer:

``` bash
composer require MinterTeam/laravel-varnish
```
For laravel >=5.5 you don't need to add service provider to config. This package supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).


To publish config file you need run command:
```bash
php artisan vendor:publish --provider="Minter\Varnish\ServiceProvider" --tag="config"
```
In the published `varnish.php` config file you need to set the `management_uri` to the host where you have a varnish, that listen managment requests

Next you need to add middleware to use it:
```php
// app/Http/Kernel.php

protected $routeMiddleware = [
...
   'varnishcache' => \Minter\Varnish\Middleware\AddCacheHeaders::class,
];
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.