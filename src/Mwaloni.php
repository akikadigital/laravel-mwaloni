<?php

namespace Akika\LaravelMwaloni;

use MwaloniConnect;

class Mwaloni
{
    /// Use the MwaloniConnect trait
    use MwaloniConnect;

    /// Define the properties
    public $environment = "";
    public $username = "";
    public $password = "";
    public $apiKey = "";
    public $serviceId = "";
    public $baseUrl = "";
    public $debugMode = false;

    /// Mwaloni Constructor
    public function __construct()
    {
        $this->environment = config('mwaloni.env');
        $this->serviceId = config('mwaloni.' . $this->environment . '.service_id');
        $this->username = config('mwaloni.' . $this->environment . '.username');
        $this->password = config('mwaloni.' . $this->environment . '.password');

        /// Set the debug mode
        $this->debugMode = config('mwaloni.debug');

        /// Generate the API key
        $this->apiKey = $this->generateApiKey();

        /// Set the base URL based on the environment
        if ($this->environment == 'production') {
            $this->baseUrl = "https://wallet.mwaloni.com/api/";
        } else {
            $this->baseUrl = "https://wallet-stg.mwaloni.com/api/";
        }
    }

    /**
     * 
     * Fetch the balance of the service
     * 
     * @return mixed
     */
    public function fetchBalance()
    {
        /// Prepare the request body
        $body = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'fetch-service-balance');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('fetchBalance request: ' . json_encode($body));
            info('fetchBalance result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a mobile number
     * 
     * @param string $destination {mobile|bank} - The destination of the money
     * @param string $orderNumber - The order number
     * @param string $accountName - The name of the account holder
     * @param string $accountNumber - The mobile number, till number or paybill number
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $countryCode - The country code
     * @param string $description - The description of the transaction
     * @param string $accountReference - The account reference
     * 
     * @return mixed
     */
    public function mobile($destination, $orderNumber, $accountName, $accountNumber, $amount, $currencyCode, $countryCode, $description, $accountReference)
    {
        $channel = "daraja";
        if ($destination != "mobile") {
            if ($accountReference) $channel .= "-paybill";
            else $channel .= "-till";
        }

        /// Prepare the request body
        $body = [
            'channel' => $channel,
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'order_number' => $orderNumber,
            'amount' => $amount,
            'account_name' => $accountName,
            'account_number' => $accountNumber,
            'account_reference' => $accountReference,
            'description' => $description,
            'country_code' => $countryCode,
            'currency_code' => $currencyCode,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('sendMoney request: ' . json_encode($body));
            info('sendMoney result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a mobile number
     * 
     * @param string $orderNumber - The order number
     * @param string $accountName - The name of the account holder
     * @param string $accountNumber - The mobile number, till number or paybill number
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $countryCode - The country code
     * @param string $description - The description of the transaction
     * @param string $accountReference - The account reference
     * 
     * @return mixed
     */

    public function ift($orderNumber, $accountNumber, $accountName, $amount, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'ift',
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            "account_name" => $accountName,
            "account_number" => $accountNumber,
            "amount" => $amount,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('sendPesalink request: ' . json_encode($body));
            info('sendPesalink result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account
     * 
     * @param string $orderNumber - The order number
     * @param string $accountNumber - The account number
     * @param string $accountName - The name of the account holder
     * @param string $bankCode - The bank code
     * @param string $bankCountryCode - The country code of the bank
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function eft($orderNumber, $accountNumber, $accountName, $bankCode, $bankCountryCode, $amount, $currencyCode, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'eft',
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            "country_code" => $bankCountryCode,
            "account_name" => $accountName,
            "bank_code" => $bankCode,
            "account_number" => $accountNumber,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('eft request: ' . json_encode($body));
            info('eft result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account
     * 
     * @param string $orderNumber - The order number
     * @param string $accountNumber - The account number
     * @param string $accountName - The name of the account holder
     * @param string $bankCode - The bank code
     * @param string $bankCountryCode - The country code of the bank
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */
    public function pesalink($orderNumber, $accountNumber, $accountName, $bankCode, $bankCountryCode, $amount, $currencyCode, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'pesalink',
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            "country_code" => $bankCountryCode,
            "account_name" => $accountName,
            "bank_code" => $bankCode,
            "account_number" => $accountNumber,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('sendPesalink request: ' . json_encode($body));
            info('sendPesalink result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account
     * 
     * @param string $orderNumber - The order number
     * @param string $accountNumber - The account number
     * @param string $accountName - The name of the account holder
     * @param string $bankCode - The bank code
     * @param string $bankCountryCode - The country code of the bank
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function rtgs($orderNumber, $accountNumber, $accountName, $bankCode, $bankCountryCode, $amount, $currencyCode, $description)
    {
        $body = [
            'channel' => 'rtgs',
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            "country_code" => $bankCountryCode,
            "account_name" => $accountName,
            "bank_code" => $bankCode,
            "account_number" => $accountNumber,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('rtgs request: ' . json_encode($body));
            info('rtgs result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Fetch the status of a transaction
     * 
     * @param string $orderNumber
     * 
     * @return mixed
     */

    public function getStatus($orderNumber)
    {
        /// Prepare the request body
        $body = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'order_number' => $orderNumber,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'fetch-transaction-status');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('getStatus request: ' . json_encode($body));
            info('getStatus result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Fetch the status of a transaction
     * 
     * @param string $orderNumber
     * 
     * @return mixed
     */

    public function contactLookup($phone)
    {
        /// Prepare the request body
        $body = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'phone_number' => $phone,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'contact-lookup');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('contactLookup request: ' . json_encode($body));
            info('contactLookup result: ' . $result);
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send an SMS
     * 
     * @param string $phone
     * @param string $message
     * 
     * @return mixed
     */
    public function sendSms($phone, $message)
    {
        /// Prepare the request body
        $body = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'phone_number' => $phone,
            'message' => $message,
        ];

        /// Make the request
        $result = $this->makeRequest($body, 'api-send-sms');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('sendSms request: ' . json_encode($body));
            info('sendSms result: ' . $result);
        }

        /// Return the result
        return $result;
    }
}
