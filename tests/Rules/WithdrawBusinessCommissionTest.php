<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Service;

use FeeCalculator\Rules\WithdrawBusinessCommission;
use PHPUnit\Framework\TestCase;
use FeeCalculator\Models\Transaction;

class WithdrawBusinessCommissionTest extends TestCase
{
    public function testDefaultValueForNonWithdrawOperations()
    {
        $transaction = $this->createStub(Transaction::class);
        $transaction
            ->method("isWithdraw")
            ->willReturn(false);
        $sut = new WithdrawBusinessCommission();
        $this->assertEquals(0, $sut->apply($transaction));
    }

    public function testCorrectFeeCalculation()
    {
        $transaction = $this->createStub(Transaction::class);
        $transaction
            ->method("isWithdraw")
            ->willReturn(true);
        $transaction
            ->method("isBusiness")
            ->willReturn(true);
        $transaction
            ->method("getAmount")
            ->willReturn(100.00);
        $sut = new WithdrawBusinessCommission();
        $this->assertEquals(0.5, $sut->apply($transaction));
    }
}
