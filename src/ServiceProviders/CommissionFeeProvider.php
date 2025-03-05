<?php

declare(strict_types=1);

namespace FeeCalculator\ServiceProviders;

use FeeCalculator\Contracts\Converter;
use FeeCalculator\HttpClients\ExchangeRatesClient;
// use FeeCalculator\Models\EuroConverter;
use FeeCalculator\Rules\DepositCommission;
use FeeCalculator\Rules\WithdrawBusinessCommission;
use FeeCalculator\Rules\WithdrawPrivateCommission;
use FeeCalculator\Service\CalculateCommissionFee;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CommissionFeeProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [
            CalculateCommissionFee::class,
        ];

        return in_array($id, $services, true);
    }

    public function register(): void
    {
        $container = $this->getContainer();
        // $container->add(Converter::class, EuroConverter::class);
        $container->add(Converter::class, ExchangeRatesClient::class);
        // below we autowire(autoresolve) the Converter interface dependency defined above
        $withdrawPrivateRule = $this->container->get(WithdrawPrivateCommission::class);
        $container->add(CalculateCommissionFee::class, CalculateCommissionFee::class)
            ->addArgument([
                new DepositCommission(),
                new WithdrawBusinessCommission(),
                $withdrawPrivateRule,
                ]);
    }
}
