<?php

namespace Akika\LaravelMwaloni\Traits;

use GuzzleHttp\Client;
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
        $encryption_key = config('mwaloni.encryption_key');
        $ciphertext  = openssl_encrypt($plaintext, 'AES-256-CTR', $this->apiKey, OPENSSL_RAW_DATA, $encryption_key);
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
        if(version_compare(app()->version(), '8.0.0', '>=')) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . $end_point, $data);

            return $response->json();
        } else {
            $client = new Client();
            $response = $client->post($this->baseUrl . $end_point, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        }
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
