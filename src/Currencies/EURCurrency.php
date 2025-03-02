<?php

namespace FeeCalculator\Currencies;

class EURCurrency
{
    protected const EURO_RATE = 1;

    public function convertToEuro(float $amount): float
    {
        return $amount * self::EURO_RATE;
    }

    public function convertFromEuro(float $amount): float
    {
        return $amount;
    }
}