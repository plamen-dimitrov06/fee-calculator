<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Service;

use FeeCalculator\Contracts\Transactionable;
use FeeCalculator\Service\CalculateCommissionFee;
use PHPUnit\Framework\TestCase;
use FeeCalculator\Rules\DepositCommission;
use FeeCalculator\Rules\WithdrawBusinessCommission;
use FeeCalculator\Rules\WithdrawPrivateCommission;
use FeeCalculator\Models\Transaction;

class CalculateCommissionFeeTest extends TestCase
{
    private static CalculateCommissionFee $sut;

    /**
     * Using this hook instead of setUp since that is executed on every data set.
     * We need to run it just once or the commission allowance we store in memory is lost.
     */
    public static function setUpBeforeClass(): void
    {
        self::$sut = new CalculateCommissionFee(array(
            new DepositCommission(),
            new WithdrawBusinessCommission(),
            new WithdrawPrivateCommission()
            )
        );
    }

    /**
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(Transactionable $transaction, string $expected)
    {
        $sut = self::$sut;
        $actual = $sut($transaction);
        $this->assertEquals($expected, (string) $actual);
    }

    public function dataProviderForAddTesting()
    {
        $filepath = "tests/Service/fixtures/input.csv";
        $handler = fopen($filepath,"r");
        if ($handler === false)
        { 
            throw new \InvalidArgumentException("Invalid file provider : {$filepath}");
        }
        while (true)
        {
            $line = fgetcsv($handler);
            if ($line === false) break;
            $transaction = new Transaction($line);
            yield array($transaction, $line[6]);
        }
    }
}
