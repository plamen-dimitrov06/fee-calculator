<?php

declare(strict_types=1);

namespace FeeCalculator\Contracts;

interface Converter
{
    /**
     * @throws \FeeCalculator\Exceptions\ConversionException
     */
    public function convert(string $from, string $to, float $amount): float;
}
