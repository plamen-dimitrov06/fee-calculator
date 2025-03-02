<?php

declare(strict_types=1);

namespace FeeCalculator\Contracts;

interface Converter
{
    public function convert(string $from, string $to, float $amount): float;
}
