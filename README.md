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

NOTE: The mpesa.php config file sets the default `MPESA_ENV` value to `sandbox`. This will always load sandbox urls.

## Function Responses

All responses, except the token generation response, conform to the responses documented on the daraja portal.

## Usage

### Initializing Mpesa

```php
use Akika\LaravelMwaloni\Mwaloni;

$mwaloni = new Mwaloni();
```
