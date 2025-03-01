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
        return $transaction->isWithdraw() && $transaction->isPrivate()
        ? $this->calculateCommission($transaction)
        : 0;
    }

    /**
     * @TODO Break commission calculations into two functions, one boolean and one for calculating the commission.
     * @param \FeeCalculator\Contracts\Transaction $transaction
     */
    protected function calculateCommission(Transaction $transaction): float
    {
        $amount = $transaction->getAmount();
        $key = $transaction->getAllowanceKey();
        $availableCredit = $amount;
        $availableCreditCounter = 1;
        $isCommissionRequired = true;
        if (isset($this->commissionAllowance[$key]))
        {
            $availableCredit += $this->commissionAllowance[$key]['amount'];
            $availableCreditCounter += $this->commissionAllowance[$key]['counter'];
        }

        if ($availableCredit <= 1000 && $availableCreditCounter <= 3)
        {
            $isCommissionRequired = false;
        }

        $commissionBase = $amount;
        if ($amount > 1000 && $isCommissionRequired)
        {
            $availableCredit = 1000;
            $commissionBase = $amount - 1000;
        }

        $this->commissionAllowance[$key] = array(
            'amount' => $availableCredit,
            'counter' => $availableCreditCounter
        );

        return $isCommissionRequired
        ? ($commissionBase * 0.3) / 100
        : 0;
    }
}
