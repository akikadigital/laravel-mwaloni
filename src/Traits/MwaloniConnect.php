<?php

namespace Akika\LaravelMwaloni\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

trait MwaloniConnect
{

    /**
     * Make a request to the Mwaloni API
     * 
     * @param array $data
     * @param string $end_point
     * @return mixed
     */
    public function makeRequest($data, $end_point)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'x-api-key' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiToken,
        ];

        if (version_compare(app()->version(), '8.0.0', '>=')) {
            $response = Http::withHeaders($headers)->post($this->baseUrl . $end_point, $data);

            return $response->json();
        } else {
            $client = new Client();
            $response = $client->post($this->baseUrl . $end_point, [
                'headers' => $headers,
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        }
    }
}
