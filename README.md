## Laravel Mwaloni Package by [Mwaloni Limited](https://mwaloni.com)

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
MWALONI_ENCRYPTION_KEY=
MWALONI_SERVICE_ID=
MWALONI_USERNAME=
MWALONI_PASSWORD=
MWALONI_SANDBOX_SERVICE_ID=
MWALONI_SANDBOX_USERNAME=
MWALONI_SANDBOX_PASSWORD=
```

NOTE:

- The mwaloni.php config file sets the default `MWALONI_ENV` value to `sandbox`. This will always load sandbox url and credentials.
- It also sets the default debug mode to true. When true, the package will log into the log destination provided
- The other variables except `MWALONI_ENV` and `MWALONI_DEBUG` will be provided by Mwaloni during onboarding.
- When debug mode is set to true, Mwaloni will log both data object and result on every API call

## Function Responses

All responses, will be in json format as received from the Mwaloni portal.

### Sample failed response

```bash
{
   "status":"01",
   "message":"Service not found",
   "balance":0
}
```

### Sample successful transaction response

```bash
{
   "status":"00",
   "message":"Cashout was successful."
}
```

## Variables definition

```php
* @param string $orderNumber - The order number e.g. AAA0001
* @param string $accountNumber - The account number to send money to
* @param string $phoneNumber - The phone number to send money to
* @param string $accountName - The name of the account holder
* @param string $bankCode - The bank code
* @param string $bankCountryCode - The country code of the bank
* @param float  $amount - The amount to send
* @param string $currencyCode - The currency code
* @param string $description - The description of the transaction
```

## Usage

### Initializing Mwaloni

To initialize Mwaloni, paste the following code within your code.

```php
use Akika\LaravelMwaloni\Mwaloni; // Paste before class definition

$mwaloni = new Mwaloni(); // Paste code where appropriate in your code.
```

### Query account balance

```php
$response = $mwaloni->fetchBalance();
```

A successful balance query response will have the below structure:

```bash
{
   "status":"00",
   "message":"Success",
   "balance":3250544,
   "balance_breakdown":{
      "utility_balance":3250544,
      "working_balance":0
   }
}
```

### Send money to mpesa enabled lines

```php
$response = $mwaloni->mobile($orderNumber, $phoneNumber, $amount, $description);
```

### Send to mpesa till number

```php
$response = $mwaloni->till($orderNumber, $accountName, $accountNumber, $amount, $description);
```

### Send to mpesa paybill

```php
$response = $mwaloni->paybill($accountReference, $orderNumber, $accountName, $accountNumber, $amount, $description);
```

### Send to bank via ift

```php
$response = $mwaloni->ift($orderNumber, $accountNumber, $accountName, $amount, $description);
```

### Send to bank via eft

```php
$response = $mwaloni->eft($orderNumber, $accountNumber, $accountName, $bankCode, $bankCountryCode, $currencyCode,  $amount, $description);
```

### Send to bank via pesalink

```php
$response = $mwaloni->pesalink($orderNumber, $accountNumber, $accountName, $bankCode, $bankName, $bankCountryCode, $currencyCode, $amount, $description)
```

### Send to bank via rtgs

```php
$response = $mwaloni->rtgs($orderNumber, $accountNumber, $accountName, $bankCode, $bankName, $swiftCode, $bankCountryCode, $currencyCode, $amount, $description)
```

### Query transaction status

```php
$response = $mwaloni->getStatus($orderNumber);
```

### Perform contact lookup

```php
$response = $mwaloni->contactLookup($phone);
```
