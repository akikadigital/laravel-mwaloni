<?php

namespace Akika\LaravelMwaloni\Traits;

use Illuminate\Support\Facades\Http;

trait MwaloniConnect
{
    /**
     * Encrypt the plaintext
     * 
     * @param string $plaintext
     * @return string
     */

    public function encrypt($plaintext)
    {
        $ciphertext  = openssl_encrypt($plaintext, 'AES-256-CTR', $this->apiKey, OPENSSL_RAW_DATA, "w4^dgd$%^62:)dgs");
        return bin2hex($ciphertext);
    }

    /**
     * Make a request to the Mwaloni API
     * 
     * @param array $data
     * @param string $end_point
     * @return mixed
     */
    public function makeRequest($data, $end_point)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $end_point, $data);

        return $response->json();
    }

    /**
     * Generate an API key
     * 
     * @return string
     */
    public function generateApiKey()
    {
        return uniqid(date('Ymdhis'), true);
    }
}
