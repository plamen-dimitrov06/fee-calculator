<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Service;

use FeeCalculator\Contracts\Transaction;
use FeeCalculator\Service\CalculateCommissionFee;
use PHPUnit\Framework\TestCase;
use FeeCalculator\Rules\DepositCommission;
use FeeCalculator\Rules\WithdrawBusinessCommission;
use FeeCalculator\Rules\WithdrawPrivateCommission;
use FeeCalculator\Models\Transaction as TransactionModel;

class CalculateCommissionFeeTest extends TestCase
{
    private static CalculateCommissionFee $sut;

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
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(Transaction $transaction, string $expected)
    {
        $sut = self::$sut;
        $actual = $sut($transaction);
        $this->assertEquals($expected, (string) $actual);
    }

    public function dataProviderForAddTesting()
    {
        $filepath = "tests/Service/fixtures/input.csv";
        $handler = fopen("tests/Service/fixtures/input.csv","r");
        if ($handler === false)
        { 
            throw new \InvalidArgumentException("Invalid file provider : {$filepath}");
        }
        while (true)
        {
            $line = fgetcsv($handler);
            if ($line === false) break;
            $transaction = new TransactionModel($line);
            yield array($transaction, $line[6]);
        }
    }
}
