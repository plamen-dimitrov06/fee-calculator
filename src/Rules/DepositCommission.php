<?php

declare(strict_types=1);

namespace FeeCalculator\Rules;

use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transactionable;

class DepositCommission implements Rule
{
    public function apply(Transactionable $transaction): float
    {
        return $transaction->isDeposit()
        ? ($transaction->getAmount() * 0.03) / 100
        : 0;
    }
}
