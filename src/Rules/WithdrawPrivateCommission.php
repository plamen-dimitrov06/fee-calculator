<?php

declare(strict_types=1);

namespace FeeCalculator\Rules;

use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transaction;

class WithdrawPrivateCommission implements Rule
{
    /**
     * "year.week.userID" => array('amount' => 0, 'counter' => 0)
     */
    protected array $commissionAllowance;

    public function apply(Transaction $transaction): float
    {
        return $transaction->isWithdraw() && $transaction->isPrivate() && $this->isCommissionRequired($transaction)
        ? ($transaction->getAmount() * 0.3) / 100
        : 0;
    }

    /**
     * @TODO Break commission calculations into two functions, one boolean and one for calculating the commission.
     * @param \FeeCalculator\Contracts\Transaction $transaction
     * @return void
     */
    protected function isCommissionRequired(Transaction $transaction): bool
    {
        $amount = $transaction->getAmount();
        if ($amount <= 1000)
        $key = $transaction->getAllowanceKey(); // year + week + userID
        $depositCounter = isset($this->commissionAllowance[$key]) ?: 1;
    }
}
