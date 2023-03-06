<?php
namespace App\Components\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class CurrencyApiService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getData() :string|null
    {
        $response = null;

        try {
            $data = $this->client->post(config('currency.currency_api_url'));
            $response = $data->getBody()->getContents();
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }

        return $response;
    }
}
