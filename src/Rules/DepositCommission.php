<?php

declare(strict_types=1);

namespace FeeCalculator\Service;

use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transaction;

class DepositCommission implements Rule
{
    public function apply(Transaction $transaction): float
    {
        return $transaction->isDeposit()
        ? ($transaction->getAmount() * 0.03) / 100
        : 0;
    }
}
