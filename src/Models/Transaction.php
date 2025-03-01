<?php

declare(strict_types=1);

namespace FeeCalculator\Models;

use FeeCalculator\Contracts\Transaction as ContractsTransaction;

class Transaction implements ContractsTransaction
{
    protected const DEPOSIT = 'deposit';
    protected const WITHDRAW = 'withdraw';
    protected const BUSINESS = 'business';
    protected const PRIVATE = 'private';

    protected string $type;

    protected string $customerType;

    protected float $amount;

    protected int $userID;

    protected int $dateYear;

    protected int $dateWeek;

    public function isDeposit(): bool
    {
        return $this->type === self::DEPOSIT;
    }

    public function isWithdraw(): bool
    {
        return $this->type === self::WITHDRAW;
    }

    public function isBusiness(): bool
    {
        return $this->customerType === self::BUSINESS;
    }

    public function isPrivate(): bool
    {
        return $this->customerType === self::PRIVATE;
    }

    /**
     * This key is used to track the "free of charge" commission for private users.
     * @return string
     */
    public function getAllowanceKey(): string
    {
        return join('.', array($this->dateYear, $this->dateWeek, $this->userID));
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
