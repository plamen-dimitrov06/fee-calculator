<?php

namespace FeeCalculator\ServiceProviders;

use GuzzleHttp\Client;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Client\ClientInterface;


class ExchangeRatesProvider extends AbstractServiceProvider
{
    function provides(string $id): bool {
        $services = [
            ClientInterface::class
        ];
        return in_array($id, $services);        
    }

    function register(): void {
        $conatiner = new Container();
        $conatiner->add(ClientInterface::class, Client::class);
    }
}