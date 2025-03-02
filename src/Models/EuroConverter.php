<?php

declare(strict_types=1);

namespace FeeCalculator\Models;

use InvalidArgumentException;
use FeeCalculator\Contracts\Converter;
use FeeCalculator\Currencies\EURCurrency;
use FeeCalculator\Currencies\USDCurrency;
use FeeCalculator\Currencies\JPYCurrency;

class EuroConverter implements Converter
{
    protected array $currencies;

    public function __construct(array $currencies = null) {
        $this->currencies = $currencies ?? array(
            'EUR_TO_EUR' => new EURCurrency(),
            'USD_TO_EUR' => new USDCurrency(),
            'JPY_TO_EUR' => new JPYCurrency(),
        );
    }

    public function convert(string $from, string $to, float $amount): float
    {
        $key = "{$from}_TO_{$to}";
        $inverseKey = "{$to}_TO_{$from}";
        if (isset($this->currencies[$key])) {
            return $this->currencies[$key]->convertToEuro($amount);
        }
        if (isset($this->currencies[$inverseKey])) {
            return $this->currencies[$inverseKey]->convertFromEuro($amount);
        }

        throw new InvalidArgumentException(
            sprintf('Invalid conversion from %s to %s', $from, $to)
        );
    }
}