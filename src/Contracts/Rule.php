<?php

declare(strict_types=1);

namespace FeeCalculator\Contracts;

interface Rule
{
    public function apply(Transactionable $transaction): float;
}
