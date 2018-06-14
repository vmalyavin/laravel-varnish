# laravel-varnish

## Installation

Install the package via composer:

``` bash
composer require MinterTeam/laravel-varnish
```

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