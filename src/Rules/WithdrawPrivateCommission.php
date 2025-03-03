<?php

declare(strict_types=1);

namespace FeeCalculator\Rules;

use FeeCalculator\Contracts\Converter;
use FeeCalculator\Contracts\Rule;
use FeeCalculator\Contracts\Transactionable;
use FeeCalculator\Models\EuroConverter;

class WithdrawPrivateCommission implements Rule
{
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

        if ($availableCredit <= 1000 && $availableCreditCounter <= 3) {
            $isCommissionRequired = false;
        }

        $commissionBase = $transaction->getAmount();
        if ($isCommissionRequired) {
            $deductable = $this->converter
                ->convert('EUR', $transaction->getCurrency(), 1000);
            $availableCredit = $this->converter
                ->convert('EUR', $transaction->getCurrency(), $availableCredit);

            $commissionBase = $availableCredit - $deductable;
            $availableCredit = 1000;
        }

        $this->commissionAllowance[$transaction->getAllowanceKey()] = [
            'amount' => $availableCredit,
            'counter' => $availableCreditCounter,
        ];

        return $isCommissionRequired
        ? ($commissionBase * 0.3) / 100
        : 0;
    }

    protected function getAvailableCredit(Transactionable $transaction)
    {
        $availableCredit = $this->converter
            ->convert($transaction->getCurrency(), 'EUR', $transaction->getAmount());

        $key = $transaction->getAllowanceKey();
        if (isset($this->commissionAllowance[$key])) {
            $availableCredit += $this->commissionAllowance[$key]['amount'];
        }

        return $availableCredit;
    }

    protected function getAvailableCreditCounter(Transactionable $transaction)
    {
        $availableCreditCounter = 1;

        $key = $transaction->getAllowanceKey();
        if (isset($this->commissionAllowance[$key])) {
            $availableCreditCounter += $this->commissionAllowance[$key]['counter'];
        }

        return $availableCreditCounter;
    }
}
