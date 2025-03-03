<?php

declare(strict_types=1);

namespace FeeCalculator\Currencies;

class DollarCurrency
{
    /**
     * This is the rate from the task description.
     */
    protected const EURO_RATE = 1.1497;

    public function convertToEuro(float $amount): float
    {
        return round($amount / self::EURO_RATE, 2);
    }

    public function convertFromEuro(float $amount): float
    {
        return round($amount * self::EURO_RATE, 2);
    }
}
