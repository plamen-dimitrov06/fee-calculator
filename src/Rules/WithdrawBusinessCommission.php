<?php

declare(strict_types=1);

namespace FeeCalculator\Rules;

use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transaction;

class WithdrawBusinessCommission implements Rule
{
    public function apply(Transaction $transaction): float
    {
        return $transaction->isWithdraw() && $transaction->isBusiness()
        ? ($transaction->getAmount() * 0.5) / 100
        : 0;
    }
}
