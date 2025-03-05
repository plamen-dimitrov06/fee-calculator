<?php

namespace FeeCalculator\HttpClients;

use Exception;
use FeeCalculator\Contracts\Converter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class ExchangeRatesClient implements Converter
{
    protected const ENDPOINT = 'https://api.apilayer.com/exchangerates_data/convert';

    protected ClientInterface $httpClient;

    protected RequestFactoryInterface $requestFactory;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $requestFactory) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function convert(string $from, string $to, float $amount): float
    {
        $endpoint = self::ENDPOINT . "?to={$to}&from={$from}&amount={$amount}";
        $request = $this->requestFactory->createRequest('GET', $endpoint);
        $apiKey = getenv('EXCHANGE_RATE_API_KEY') ?:'mS7RbazHk87lRHAjfCAL23hQhQiCqRHS';
        // @TODO refactor below so we don't to $req = $req twice
        $request = $request->withHeader('Content-Type', 'text/plain');
        $request = $request->withHeader('apikey', $apiKey);
        $response = $this->httpClient->sendRequest($request);
        $response = json_decode($response->getBody()->getContents(), true);
        return $response['success'] ? (float) $response['result'] : 0;
    }
}