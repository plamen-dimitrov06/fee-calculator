<?php

namespace FeeCalculator\Currencies;

class USDCurrency
{
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