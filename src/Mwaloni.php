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
     * @param string $destination
     * @param string $orderNumber
     * @param string $accountName
     * @param string $accountNumber
     * @param float $amount
     * @param string $currencyCode
     * @param string $countryCode
     * @param string $description
     * @param string $accountReference
     * 
     * @return mixed
     */
    public function sendMoney($destination, $orderNumber, $accountName, $accountNumber, $amount, $currencyCode, $countryCode, $description, $accountReference)
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
     * Send money to a bank account
     * 
     * @param string $orderNumber
     * @param string $accountNumber
     * @param string $accountName
     * @param string $bankCode
     * @param string $bankCountryCode
     * @param float $amount
     * @param string $currencyCode
     * @param string $description
     * 
     * @return mixed
     */
    public function sendPesalink($orderNumber, $accountNumber, $accountName, $bankCode, $bankCountryCode, $amount, $currencyCode, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'pesalink-bank',
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
