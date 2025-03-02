<?php

namespace FeeCalculator\Value;

class CommissionFee
{
    private float $amount;
    private int $precision;

    public function __construct(float $amount, string $currency) {
        $this->amount = $amount;
        $this->precision = $currency === "JPY" ? 0 : 2;
    }

    public function __toString(): string {
        return number_format($this->amount, $this->precision, ".", "");
    }
}