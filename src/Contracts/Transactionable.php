<?php

declare(strict_types=1);

namespace FeeCalculator\Contracts;

interface Transactionable
{
    public function isDeposit(): bool;

    public function isWithdraw(): bool;

    public function isBusiness(): bool;

    public function isPrivate(): bool;

    public function getAllowanceKey(): string;

    public function getAmount(): float;

    public function getCurrency(): string;
}
