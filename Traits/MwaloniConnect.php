<?php

use Illuminate\Support\Facades\Http;

trait MwaloniConnect
{
    public function encrypt($plaintext)
    {
        $encrypted  = openssl_encrypt($plaintext, 'AES-256-CTR', $this->apiKey, OPENSSL_RAW_DATA, "w4^dgd$%^62:)dgs");
        return bin2hex($encrypted);
    }

    public function makeRequest($data, $end_point)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $end_point, $data);

        return $response->json();
    }
}