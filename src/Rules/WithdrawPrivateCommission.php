<?php

declare(strict_types=1);

namespace FeeCalculator\Service;

use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transaction;

class WithdrawPrivateCommission implements Rule
{
    protected CurrencyConverter $converter;
    /**
     * "year.week.userID" => array('amount' => 0, 'counter' => 0)
     */
    protected array $commissionAllowance;

    public function __construct() {
        $this->converter = new CurrencyConverter();
    }

    public function apply(Transaction $transaction): float
    {
        return $transaction->isWithdraw() && $transaction->isPrivate() && $this->isCommissionRequired($transaction)
        ? ($transaction->getAmount() * 0.3) / 100
        : 0;
    }

    protected function isCommissionRequired(Transaction $transaction): bool
    {
        $amount = $transaction->getAmount();
        if ($amount <= 1000)
        $year = $transaction->getAllowanceKey(); // year + week + userID
        $depositCounter = isset($this->commissionAllowance[$key]) ?: 1;
    }
}
