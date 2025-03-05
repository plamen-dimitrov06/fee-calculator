<?php

declare(strict_types=1);

namespace FeeCalculator\HttpClients;

use FeeCalculator\Contracts\Converter;
use FeeCalculator\Exceptions\ConversionException;
use FeeCalculator\Logging\CliLogger;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

class ExchangeRatesClient implements Converter
{
    protected const ENDPOINT = 'https://api.apilayer.com/exchangerates_data/convert';

    /**
     * The actual implementation can be found in {@use ExchangeRatesProvider::class}.
     * @var ClientInterface
     */
    protected ClientInterface $httpClient;

    protected RequestFactoryInterface $requestFactory;

    protected $logger;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->logger = new CliLogger();
    }

    public function convert(string $from, string $to, float $amount): float
    {
        $endpoint = self::ENDPOINT."?to={$to}&from={$from}&amount={$amount}";
        $request = $this->buildRequest($endpoint);

        $response = $this->httpClient->sendRequest($request);
        $body = $response->getBody()->getContents();
        $this->logger->debug($body);
        $response = json_decode($body, true);

        if (!isset($response['success']))
        {
            $message = $response['message'] ?? "The service is currently unavailable.";
            $this->logger->error($message);
            throw ConversionException::withMessage($message);
        }

        return (float) $response['result'];
    }

    protected function buildRequest(string $endpoint): RequestInterface
    {
        $apiKey = getenv('EXCHANGE_RATE_API_KEY');
        return $this->requestFactory
            ->createRequest('GET', $endpoint)
            ->withHeader('Content-Type', 'text/plain')
            ->withHeader('apikey', $apiKey);
    }
}
