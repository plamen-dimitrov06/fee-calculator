<?php

declare(strict_types=1);

namespace FeeCalculator\Service;

use FeeCalculator\Contracts\Transaction;
use FeeCalculator\Value\CommissionFee;

class CalculateCommissionFee
{
    protected array $commissions = array();

    public function __construct(array $commissions)
    {
        $this->commissions = $commissions;
    }

    public function __invoke(Transaction $transaction)
    {
        $fee = 0;
        foreach ($this->commissions as $commission) {
            $fee += $commission->apply($transaction);
        }
        return new CommissionFee($fee, $transaction->getCurrency());
    }
}