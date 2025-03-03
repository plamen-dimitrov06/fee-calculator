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

    /**
     * @TODO Break commission calculations into two functions, one boolean and one for calculating the commission.
     */
    protected function calculateCommission(Transactionable $transaction): float
    {
        $amount = $this->converter->convert(
            $transaction->getCurrency(), 'EUR', $transaction->getAmount()
        );
        $key = $transaction->getAllowanceKey();
        $availableCredit = $amount;
        $availableCreditCounter = 1;
        $isCommissionRequired = true;
        if (isset($this->commissionAllowance[$key])) {
            $availableCredit += $this->commissionAllowance[$key]['amount'];
            $availableCreditCounter += $this->commissionAllowance[$key]['counter'];
        }

        if ($availableCredit <= 1000 && $availableCreditCounter <= 3) {
            $isCommissionRequired = false;
        }

        $commissionBase = $transaction->getAmount();
        if ($isCommissionRequired) {
            $deductable = $this->converter->convert(
                'EUR', $transaction->getCurrency(), 1000
            );
            $availableCredit = $this->converter->convert('EUR', $transaction->getCurrency(), $availableCredit);

            $commissionBase = $availableCredit - $deductable;
            $availableCredit = 1000;
        }

        $this->commissionAllowance[$key] = [
            'amount' => $availableCredit,
            'counter' => $availableCreditCounter,
        ];

        return $isCommissionRequired
        ? ($commissionBase * 0.3) / 100
        : 0;
    }
}
