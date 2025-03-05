<?php

declare(strict_types=1);

use FeeCalculator\ServiceProviders\CommissionFeeProvider;
use FeeCalculator\ServiceProviders\ExchangeRatesProvider;
use League\Container\Container;
use FeeCalculator\Models\Transaction;
use FeeCalculator\Service\CalculateCommissionFee;
use Psr\Http\Client\ClientInterface;

include "./vendor/autoload.php";

class Script
{
    public function __construct($filename)
    {
        $handler = fopen($filename,"r");
        if ($handler === false)
        { 
            throw new InvalidArgumentException("Invalid file provider : {$filename}");
        }
        // @TODO this is too long, refactor it
        $container = new Container();
        $container->addServiceProvider(new ExchangeRatesProvider());
        $container->addServiceProvider(new CommissionFeeProvider());
        $container->delegate(new League\Container\ReflectionContainer());
        $container->get(ClientInterface::class);
        $feeCalculator = $container->get(CalculateCommissionFee::class);
        while (true)
        {
            $line = fgetcsv($handler);
            if ($line === false) break;
            $transaction = new Transaction($line);
            $commissionFee = $feeCalculator($transaction);
            echo $commissionFee . PHP_EOL;
        }

        fclose($handler);
    }
}

$script = new Script($argv[1]);