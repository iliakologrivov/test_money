<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function exchange($from, $to, $count)
    {
        try {
            $response = $this->client->request('GET', "https://api.exchangeratesapi.io/latest?base={$from}&symbols={$to}");

            $response = \GuzzleHttp\json_decode($response->getBody())->rates->$to;

            if (is_numeric($response) && $response > 0) {
                return $response * $count;
            }
        } catch (\Exception $exception) {
            throw new \Exception('Неверный ответ от exchangeratesapi.io');
        }

        throw new \Exception('Неверный ответ от exchangeratesapi.io');
    }
}