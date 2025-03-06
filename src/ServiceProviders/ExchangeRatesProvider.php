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
// use FeeCalculator\Models\EuroConverter;

class ExchangeRatesProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [
            ClientInterface::class,
            Psr17Factory::class,
            Converter::class,
        ];

        return in_array($id, $services, true);
    }

    public function register(): void
    {
        $container = $this->getContainer();
        $container->add(ClientInterface::class, Client::class);
        $container->add(RequestFactoryInterface::class, Psr17Factory::class);
        // $container->add(Converter::class, EuroConverter::class);
        $container->add(Converter::class, ExchangeRatesClient::class)
            ->addArgument(ClientInterface::class)
            ->addArgument(RequestFactoryInterface::class);
    }
}
