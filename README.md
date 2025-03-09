## Laravel Mwaloni Package by [Akika Digital](https://akika.digital)

This Laravel package provides convenient methods for integrating [Mwaloni](https://mwaloni.com) functionalities into your Laravel application.

## Installation

You can install the package via composer:

```bash
composer require akika/laravel-mwaloni
```

After installing the package, publish the configuration file using the following command:

```bash
php artisan mwaloni:install
```

This will generate a mwaloni.php file in your config directory where you can set your Mwaloni credentials and other configuration options.

## .env file Setup

Add the following configurations into the .env file

```bash
MWALONI_ENV=
MWALONI_DEBUG=
MWALONI_SERVICE_ID=
MWALONI_USERNAME=
MWALONI_PASSWORD=
MWALONI_SANDBOX_SERVICE_ID=
MWALONI_SANDBOX_USERNAME=
MWALONI_SANDBOX_PASSWORD=
```

NOTE:

-   The mwaloni.php config file sets the default `MWALONI_ENV` value to `sandbox`. This will always load sandbox urls.
-   It also sets the default debug mode to true. When true, the package will log into the log destination provided
-   Please provide the other details in the evn or the config file as provided by Mwaloni.

## Function Responses

All responses, will be in json format as received from the Mwaloni portal.

## Usage

### Initializing Mwaloni

```php
use Akika\LaravelMwaloni\Mwaloni;

$mwaloni = new Mwaloni($baseUrl, $serviceId, $username, $password);
```
