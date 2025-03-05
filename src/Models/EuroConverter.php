<?php

declare(strict_types=1);

namespace FeeCalculator\Models;

use FeeCalculator\Contracts\Converter;
use FeeCalculator\Currencies\DollarCurrency;
use FeeCalculator\Currencies\EuroCurrency;
use FeeCalculator\Currencies\YenCurrency;
use FeeCalculator\Exceptions\ConversionException;

class EuroConverter implements Converter
{
    protected array $currencies;

    public function __construct(array $currencies = null)
    {
        $this->currencies = $currencies ?? [
            'EUR_TO_EUR' => new EuroCurrency(),
            'USD_TO_EUR' => new DollarCurrency(),
            'JPY_TO_EUR' => new YenCurrency(),
        ];
    }

    public function convert(string $from, string $to, float $amount): float
    {
        $key = strtoupper("{$from}_TO_{$to}");
        $inverseKey = strtoupper("{$to}_TO_{$from}");
        if (isset($this->currencies[$key])) {
            return $this->currencies[$key]->convertToEuro($amount);
        }
        if (isset($this->currencies[$inverseKey])) {
            return $this->currencies[$inverseKey]->convertFromEuro($amount);
        }

        throw ConversionException::withMessage(sprintf('Invalid conversion from %s to %s', $from, $to));
    }
}
