<?php

namespace App\Services;

use App\Helpers\Currencies;
use GuzzleHttp\Client;

class ExchangeService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function exchangeTo($to, $count)
    {
        try {
            $response = $this->client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD&symbols=' . $to);

            $response = \GuzzleHttp\json_decode($response->getBody())->rates->$to;

            if (is_numeric($response) && $response > 0) {
                return $response * $count;
            }
        } catch (\Exception $exception) {
            throw new \Exception('Неверный ответ от exchangeratesapi.io');
        }

        throw new \Exception('Неверный ответ от exchangeratesapi.io');
    }

    public function exchange($from, $to, $count)
    {
        if ($from != Currencies::ACCOUNT_CURRENCY_USD && $to != Currencies::ACCOUNT_CURRENCY_USD) {
            return $this->exchangeTo($to, $this->exchangeTo($from, $count));
        }

        return $this->exchangeTo(Currencies::ACCOUNT_CURRENCY_USD == $from ? $to : $from, $count);
    }
}