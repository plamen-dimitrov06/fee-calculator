<?php

declare(strict_types=1);

use FeeCalculator\Exceptions\ConversionException;
use FeeCalculator\ServiceProviders\CommissionFeeProvider;
use FeeCalculator\ServiceProviders\ExchangeRatesProvider;
use League\Container\Container;
use FeeCalculator\Models\Transaction;
use FeeCalculator\Service\CalculateCommissionFee;
use League\Container\ReflectionContainer;

include "./vendor/autoload.php";

class Script
{
    protected const ERROR_MESSAGE = "There was an error calculating the commission fee. Please try again later.";

    public function __construct($filename)
    {
        $handler = fopen($filename,"r");
        if ($handler === false)
        { 
            throw new InvalidArgumentException("Invalid file provider : {$filename}");
        }

        $feeCalculator = $this->configureContainer();
        while (true)
        {
            $line = fgetcsv($handler);
            if ($line === false) break;
            $transaction = new Transaction($line);
            try
            {
                $commissionFee = $feeCalculator($transaction);
            } catch (ConversionException $e)
            {
                echo $e->getMessage();
            } catch (Throwable $e)
            {
                echo self::ERROR_MESSAGE;
            }
            echo $commissionFee . PHP_EOL;
        }

        fclose($handler);
    }

    /**
     * This method also returns the fee calculator service
     * after configuring the container.
     */
    protected function configureContainer()
    {
        $container = new Container();
        $container->addServiceProvider(new ExchangeRatesProvider());
        $container->addServiceProvider(new CommissionFeeProvider());
        $container->delegate(new ReflectionContainer());
        return $container->get(CalculateCommissionFee::class);
    }
}

$script = new Script($argv[1]);