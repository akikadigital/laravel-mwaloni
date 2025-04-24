<?php

namespace Akika\LaravelMwaloni;

use Akika\LaravelMwaloni\Traits\MwaloniConnect;

class Mwaloni
{
    /// Use the MwaloniConnect trait
    use MwaloniConnect;

    /// Define the properties
    public $environment = "";
    public $username = "";
    public $password = "";
    public $apiKey = "";
    public $apiToken = "";
    public $serviceId = "";
    public $baseUrl = "";
    public $debugMode = false;

    /**
     * 
     * Initialize the Mwaloni class using credentials provided
     * 
     * @param string $serviceId
     * @param string $username
     * @param string $password
     * @param string $apiKey
     */
    public function __construct($serviceId, $username, $password, $apiKey)
    {
        $this->serviceId = $serviceId;
        $this->username = $username;
        $this->password = $password;
        $this->apiKey = $apiKey;

        $this->environment = config('mwaloni.env');

        /// Set the debug mode
        $this->debugMode = config('mwaloni.debug');

        // Set the base URL based on the environment
        if ($this->environment == 'production') {
            $this->baseUrl = "https://wallet.mwaloni.com/api/";
        } else {
            $this->baseUrl = "https://wallet-stg.mwaloni.com/api/";
        }

        if ($this->debugMode) {
            info('------------------- Initiliazing Mwaloni -------------------');
            info('API URL: ' . $this->baseUrl);
        }
    }

    /**
     * 
     * Set the serviceId
     * 
     * @param string $serviceId
     */

    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;

        if ($this->debugMode) {
            info('------------------- Set Service ID -------------------');
            info('Service ID: ' . $this->serviceId);
        }
    }

    /**
     * 
     * Set the API token
     * 
     * @param string $token
     */

    public function setToken($token)
    {
        $this->apiToken = $token;

        if ($this->debugMode) {
            info('------------------- Set Token -------------------');
            info('Token: ' . $this->apiToken);
        }
    }

    /**
     * 
     * Authenticate the user
     * 
     * @return mixed
     */

    public function authenticate()
    {
        $body = [
            "username" => $this->username,
            "password" => $this->password
        ];

        // validation
        if (empty($this->username) || empty($this->password)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, "authenticate");

        // Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info("------------------- Authenticate -------------------");
            info("fetchBalance request: " . json_encode($body));
            info("fetchBalance result: " . json_encode($result));
        }

        /// Return the result
        return $result;
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
            "service_id" => $this->serviceId,
        ];

        // validation
        if (empty($this->serviceId)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, "get-balance");

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info("------------------- Authenticate -------------------");
            info("fetchBalance request: " . json_encode($body));
            info("fetchBalance result: " . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a mobile number
     * 
     * @param string $orderNumber - The order number
     * @param string $phoneNumber - The phone number
     * @param float $amount - The amount to send
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function mobile($orderNumber, $phoneNumber, $amount, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'daraja-mobile',
            'service_id' => $this->serviceId,
            'order_number' => $orderNumber,
            'amount' => $amount,
            'account_number' => $phoneNumber,
            'description' => $description,
            'country_code' => "KE",
            'currency_code' => "KES",
        ];

        // validation
        if (empty($orderNumber) || empty($phoneNumber) || empty($amount) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('mobile request: ' . json_encode($body));
            info('mobile request: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a till number
     * 
     * @param string $orderNumber - The order number
     * @param string $accountName - The name of the account holder
     * @param string $accountNumber - The till number
     * @param float $amount - The amount to send
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function till($orderNumber, $accountName, $accountNumber, $amount, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'daraja-till',
            'service_id' => $this->serviceId,
            'order_number' => $orderNumber,
            'amount' => $amount,
            'account_name' => $accountName,
            'account_number' => $accountNumber,
            'description' => $description,
        ];

        // validation
        if (empty($orderNumber) || empty($accountNumber) || empty($amount) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('till request: ' . json_encode($body));
            info('till result: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a paybill number
     * 
     * @param string $accountReference - The account reference
     * @param string $orderNumber - The order number
     * @param string $accountName - The name of the account holder
     * @param string $accountNumber - The paybill number
     * @param float $amount - The amount to send
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function paybill($accountReference, $orderNumber, $accountName, $accountNumber, $amount, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'daraja-paybill',
            'service_id' => $this->serviceId,
            'order_number' => $orderNumber,
            'amount' => $amount,
            'account_name' => $accountName,
            'account_number' => $accountNumber,
            'account_reference' => $accountReference,
            'description' => $description,
        ];

        // validation
        if (empty($accountReference) || empty($orderNumber) || empty($accountNumber) || empty($amount) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('paybill request: ' . json_encode($body));
            info('paybill result: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account through ift
     * 
     * @param string $orderNumber - The order number
     * @param string $accountName - The name of the account holder
     * @param string $accountNumber - The account number
     * @param string $address - The address of the account holder
     * @param string $countryCode - The country code
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function ift($orderNumber, $accountName, $accountNumber, $address, $countryCode, $amount, $currencyCode, $description)
    {
        /// Prepare the request body
        $body = [
            "channel" => "ift",
            "service_id" => $this->serviceId,
            "account_name" => $accountName,
            "account_number" => $accountNumber,
            "address" => $address,
            "country_code" => $countryCode,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        // validation
        if (empty($orderNumber) || empty($accountName) || empty($accountNumber) || empty($address) || empty($countryCode) || empty($amount) || empty($currencyCode) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('ift request: ' . json_encode($body));
            info('ift result: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account through eft
     * 
     * @param string $orderNumber - The order number
     * @param string $accountNumber - The account number
     * @param string $accountName - The name of the account holder
     * @param string $bankCode - The bank code
     * @param string $bankName - The name of the bank
     * @param string $bankCountryCode - The country code of the bank
     * @param string $bankCIF - The CIF/Swift Code of the bank
     * @param string $accountAddress - The address of the account holder
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function eft($orderNumber, $accountNumber, $accountName, $bankCode, $bankName, $bankCountryCode, $bankCIF, $accountAddress, $amount, $currencyCode, $description)
    {
        /// Prepare the request body
        $body = [
            "channel" => "eft",
            "service_id" => $this->serviceId,
            "country_code" => $bankCountryCode,
            "account_name" => $accountName,
            "bank_code" => $bankCode,
            "bank_name" => $bankName,
            "bank_cif" => $bankCIF,
            "address" => $accountAddress,
            "account_number" => $accountNumber,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        // validation
        if (empty($orderNumber) || empty($accountNumber) || empty($accountName) || empty($bankCode) || empty($bankName) || empty($bankCountryCode) || empty($bankCIF) || empty($accountAddress) || empty($amount) || empty($currencyCode) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('eft request: ' . json_encode($body));
            info('eft result: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account through pesalink
     * 
     * @param string $orderNumber - The order number
     * @param string $accountNumber - The account number
     * @param string $accountName - The name of the account holder
     * @param string $bankName - The name of the bank
     * @param string $bankCountryCode - The country code of the bank
     * @param string $bankCIF - The CIF/Swift Code of the bank
     * @param string $address - The address of the account holder
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function pesalink($orderNumber, $accountNumber, $accountName, $bankName, $bankCountryCode, $bankCIF, $address, $amount, $currencyCode, $description)
    {
        /// Prepare the request body
        $body = [
            'channel' => 'pesalink',
            "service_id" => $this->serviceId,
            "country_code" => $bankCountryCode,
            "account_name" => $accountName,
            "bank_name" => $bankName,
            "bank_cif" => $bankCIF,
            "account_number" => $accountNumber,
            "address" => $address,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        // validation
        if (empty($orderNumber) || empty($accountNumber) || empty($accountName) || empty($bankCode) || empty($bankName) || empty($bankCountryCode) || empty($bankCIF) || empty($address) || empty($amount) || empty($currencyCode) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('sendPesalink request: ' . json_encode($body));
            info('sendPesalink result: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }

    /**
     * 
     * Send money to a bank account through rtgs
     * 
     * @param string $orderNumber - The order number
     * @param string $accountNumber - The account number
     * @param string $accountName - The name of the account holder
     * @param string $bankCode - The bank code
     * @param string $bankName - The name of the bank
     * @param string $address - The address of the account holder
     * @param string $swiftCode - The swift code of the bank
     * @param string $bankCountryCode - The country code of the bank
     * @param float $amount - The amount to send
     * @param string $currencyCode - The currency code
     * @param string $description - The description of the transaction
     * 
     * @return mixed
     */

    public function rtgs($orderNumber, $accountNumber, $accountName, $bankCode, $bankName, $address, $swiftCode, $bankCountryCode, $amount, $currencyCode, $description)
    {
        $body = [
            "channel" => "rtgs",
            "service_id" => $this->serviceId,
            "country_code" => $bankCountryCode,
            "account_name" => $accountName,
            "address" => $address,
            "bank_name" => $bankName,
            "swift_code" => $swiftCode,
            "account_number" => $accountNumber,
            "amount" => $amount,
            "currency_code" => $currencyCode,
            "order_number" => $orderNumber,
            "description" => $description,
        ];

        // validation
        if (empty($orderNumber) || empty($accountNumber) || empty($accountName) || empty($bankCode) || empty($bankName) || empty($address) || empty($swiftCode) || empty($bankCountryCode) || empty($amount) || empty($currencyCode) || empty($description)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-money');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('rtgs request: ' . json_encode($body));
            info('rtgs result: ' . json_encode($result));
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
            "orderNumber" => $orderNumber,
        ];

        // validation
        if (empty($orderNumber)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'get-transaction-status');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('getStatus request: ' . json_encode($body));
            info('getStatus result: ' . json_encode($result));
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

    public function contactLookup($contact)
    {
        /// Prepare the request body
        $body = [
            "contact" => $contact,
        ];

        // validation
        if (empty($contact)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'contact-lookup');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('contactLookup request: ' . json_encode($body));
            info('contactLookup result: ' . json_encode($result));
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
            "phoneNumber" => $phone,
            "message" => $message,
        ];

        // validation
        if (empty($phone) || empty($message)) {
            return [
                "status" => "error",
                "message" => "Missing required details"
            ];
        }

        /// Make the request
        $result = $this->makeRequest($body, 'send-sms');

        /// Log the request and response if debug mode is enabled
        if ($this->debugMode) {
            info('------------------- Authenticate -------------------');
            info('sendSms request: ' . json_encode($body));
            info('sendSms result: ' . json_encode($result));
        }

        /// Return the result
        return $result;
    }
}
