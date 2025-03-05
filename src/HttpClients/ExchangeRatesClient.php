<?php

declare(strict_types=1);

namespace FeeCalculator\HttpClients;

use FeeCalculator\Contracts\Converter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class ExchangeRatesClient implements Converter
{
    protected const ENDPOINT = 'https://api.apilayer.com/exchangerates_data/convert';

    protected ClientInterface $httpClient;

    protected RequestFactoryInterface $requestFactory;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function convert(string $from, string $to, float $amount): float
    {
        $endpoint = self::ENDPOINT."?to={$to}&from={$from}&amount={$amount}";
        $apiKey = getenv('EXCHANGE_RATE_API_KEY') ?: 'mS7RbazHk87lRHAjfCAL23hQhQiCqRHS';

        $request = $this->requestFactory
            ->createRequest('GET', $endpoint)
            ->withHeader('Content-Type', 'text/plain')
            ->withHeader('apikey', $apiKey);
        $response = $this->httpClient->sendRequest($request);
        $response = json_decode($response->getBody()->getContents(), true);

        return $response['success'] ? (float) $response['result'] : 0;
    }
}
