<?php

namespace Akika\LaravelMwaloni;

use MwaloniConnect;

class Mwaloni
{
    use MwaloniConnect;

    public $username = "";
    public $password = "";
    public $apiKey = "";
    public $serviceId = "";
    public $baseUrl = "";

    public function __construct($baseUrl = null, $serviceId = null, $username = null, $password = null)
    {
        $this->baseUrl = $baseUrl;
        $this->serviceId = $serviceId;
        $this->username = $username;
        $this->password = $password;
        $this->apiKey = uniqid(date('Ymdhis'), true);
    }

    public function fetchBalance()
    {
        $data = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey
        ];

        return $this->makeRequest($data, 'fetch-service-balance');
    }

    public function sendMoney($destination, $orderNumber, $accountName, $accountNumber, $amount, $currencyCode, $countryCode, $description, $accountReference)
    {
        $channel = "daraja";
        if ($destination != "mobile") {
            if ($accountReference) $channel .= "-paybill";
            else $channel .= "-till";
        }
        $data = [
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
        return $this->makeRequest($data, 'send-money');
    }

    public function sendPesalink($orderNumber, $accountNumber, $accountName, $bankCode, $bankCountryCode, $amount, $currencyCode, $description)
    {
        $data = [
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
        return $this->makeRequest($data, 'send-money');
    }

    public function getStatus($orderNumber)
    {
        $data = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'order_number' => $orderNumber,
        ];
        return $this->makeRequest($data, 'fetch-transaction-status');
    }

    public function contactLookup($phone)
    {
        $data = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'phone_number' => $phone,
        ];
        return $this->makeRequest($data, 'contact-lookup');
    }

    public function sendSms($phone, $message)
    {
        $data = [
            'service_id' => $this->serviceId,
            'username' => $this->username,
            'password' => $this->encrypt($this->password),
            'key' => $this->apiKey,
            'phone_number' => $phone,
            'message' => $message,
        ];
        return $this->makeRequest($data, 'api-send-sms');
    }
}
