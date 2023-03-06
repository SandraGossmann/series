<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiServices
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCities() : array
    {
        $response = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/communes?boost=population'
        );

        return $response->toArray();
    }
}