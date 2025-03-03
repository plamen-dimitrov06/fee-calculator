<?php

declare(strict_types=1);

namespace FeeCalculator\Rules;

use FeeCalculator\Contracts\Converter;
use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transactionable;
use FeeCalculator\Models\EuroConverter;

class WithdrawPrivateCommission implements Rule
{
    protected const ALLOWANCE_LIMIT = 3;
    protected const ALLOWANCE_AMOUNT = 1000;
    protected const ALLOWANCE_CURRENCY = 'EUR';

    /**
     * "year.week.userID" => array('amount' => 0, 'counter' => 0).
     */
    protected array $commissionAllowance;

    protected Converter $converter;

    public function __construct(Converter $converter = null)
    {
        $this->converter = $converter ?? new EuroConverter();
    }

    public function apply(Transactionable $transaction): float
    {
        return $transaction->isWithdraw() && $transaction->isPrivate()
        ? $this->calculateCommission($transaction)
        : 0;
    }

    protected function calculateCommission(Transactionable $transaction): float
    {
        $availableCredit = $this->getAvailableCredit($transaction);
        $availableCreditCounter = $this->getAvailableCreditCounter($transaction);

        $isCommissionRequired = true;
        if ($availableCredit <= self::ALLOWANCE_AMOUNT && $availableCreditCounter <= self::ALLOWANCE_LIMIT) {
            $isCommissionRequired = false;
        }

        $commissionBase = $transaction->getAmount();
        if ($isCommissionRequired) {
            $commissionBase = $this->calculateCommissionBase($transaction, $availableCredit);
            $availableCredit = self::ALLOWANCE_AMOUNT;
        }

        $this->commissionAllowance[$transaction->getAllowanceKey()] = [
            'amount' => $availableCredit,
            'counter' => $availableCreditCounter,
        ];

        return $isCommissionRequired
        ? ($commissionBase * 0.3) / 100
        : 0;
    }

    protected function getAvailableCredit(Transactionable $transaction): float
    {
        $availableCredit = $this->converter
            ->convert($transaction->getCurrency(), self::ALLOWANCE_CURRENCY, $transaction->getAmount());

        $key = $transaction->getAllowanceKey();
        if (isset($this->commissionAllowance[$key])) {
            $availableCredit += $this->commissionAllowance[$key]['amount'];
        }

        return $availableCredit;
    }

    protected function getAvailableCreditCounter(Transactionable $transaction): int
    {
        $availableCreditCounter = 1;

        $key = $transaction->getAllowanceKey();
        if (isset($this->commissionAllowance[$key])) {
            $availableCreditCounter += $this->commissionAllowance[$key]['counter'];
        }

        return $availableCreditCounter;
    }

    /**
     * Commission is always calculated in the transaction's currency.
     */
    protected function calculateCommissionBase(Transactionable $transaction, $availableCredit): float
    {
        $deductable = $this->converter
            ->convert(self::ALLOWANCE_CURRENCY, $transaction->getCurrency(), self::ALLOWANCE_AMOUNT);
        $availableCredit = $this->converter
            ->convert(self::ALLOWANCE_CURRENCY, $transaction->getCurrency(), $availableCredit);

        return $availableCredit - $deductable;
    }
}
