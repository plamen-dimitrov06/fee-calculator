<?php

declare(strict_types=1);

namespace FeeCalculator\ServiceProviders;

use FeeCalculator\Contracts\Converter;
use FeeCalculator\HttpClients\ExchangeRatesClient;
use GuzzleHttp\Client;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class ExchangeRatesProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [
            ClientInterface::class,
            Psr17Factory::class,
        ];

        return in_array($id, $services, true);
    }

    public function register(): void
    {
        $conatiner = $this->getContainer();
        $conatiner->add(ClientInterface::class, Client::class);
        $conatiner->add(RequestFactoryInterface::class, Psr17Factory::class);
        $conatiner->add(Converter::class, ExchangeRatesClient::class)
            ->addArgument(ClientInterface::class)
            ->addArgument(RequestFactoryInterface::class);
    }
}
