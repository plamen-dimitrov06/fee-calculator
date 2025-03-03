<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Rules;

use PHPUnit\Framework\TestCase;
use FeeCalculator\Rules\DepositCommission;
use FeeCalculator\Models\Transaction;

class DepositCommissionTest extends TestCase
{
    public function testDefaultValueForNonDepositOperations()
    {
        $transaction = $this->createStub(Transaction::class);
        $transaction
            ->method("isDeposit")
            ->willReturn(false);
        $sut = new DepositCommission();
        $this->assertEquals(0, $sut->apply($transaction));
    }

    public function testCorrectFeeCalculation()
    {
        $transaction = $this->createStub(Transaction::class);
        $transaction
            ->method("isDeposit")
            ->willReturn(true);
        $transaction
            ->method("getAmount")
            ->willReturn(100.00);
        $sut = new DepositCommission();
        $this->assertEquals(0.03, $sut->apply($transaction));
    }
}
