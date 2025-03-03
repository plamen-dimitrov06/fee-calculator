<?php

declare(strict_types=1);

namespace FeeCalculator\Currencies;

class YenCurrency
{
    /**
     * This is the rate from the task description.
     */
    protected const EURO_RATE = 129.53;

    public function convertToEuro(float $amount): float
    {
        return round($amount / self::EURO_RATE);
    }

    public function convertFromEuro(float $amount): float
    {
        return round($amount * self::EURO_RATE);
    }
}
