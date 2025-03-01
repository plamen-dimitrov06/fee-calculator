<?php

declare(strict_types=1);
use FeeCalculator\Service\CalculateCommissionFee;
use FeeCalculator\Service\DepositCommission;
use FeeCalculator\Service\WithdrawBusinessCommission;
use FeeCalculator\Service\WithdrawPrivateCommission;

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
        $feeCalculator = new CalculateCommissionFee(
            array(new DepositCommission(),
            new WithdrawBusinessCommission(),
            new WithdrawPrivateCommission())
        );
        while (true)
        {
            $line = fgetcsv($handler);
            if ($line === false) break;
            $commissionFee = $feeCalculator();
            echo $commissionFee;
        }

        fclose($handler);
    }
}
$script = new Script($argv[1]);