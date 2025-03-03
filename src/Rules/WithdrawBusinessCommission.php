<?php

declare(strict_types=1);

namespace FeeCalculator\Rules;

use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transactionable;

class WithdrawBusinessCommission implements Rule
{
    public function apply(Transactionable $transaction): float
    {
        return $transaction->isWithdraw() && $transaction->isBusiness()
        ? ($transaction->getAmount() * 0.5) / 100
        : 0;
    }
}
