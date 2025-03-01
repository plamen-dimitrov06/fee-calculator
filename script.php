<?php

declare(strict_types=1);
use FeeCalculator\Models\Transaction;
use FeeCalculator\Service\CalculateCommissionFee;
use FeeCalculator\Rules\DepositCommission;
use FeeCalculator\Rules\WithdrawBusinessCommission;
use FeeCalculator\Rules\WithdrawPrivateCommission;

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
        $feeCalculator = new CalculateCommissionFee(array(
            new DepositCommission(),
            new WithdrawBusinessCommission(),
            // new WithdrawPrivateCommission()
            )
        );
        while (true)
        {
            $line = fgetcsv($handler);
            if ($line === false) break;
            $transaction = new Transaction($line);
            $commissionFee = $feeCalculator($transaction);
            echo $commissionFee;
        }

        fclose($handler);
    }
}
$script = new Script($argv[1]);