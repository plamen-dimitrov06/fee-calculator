<?php

declare(strict_types=1);

namespace FeeCalculator\Models;

use DateTime;
use FeeCalculator\Contracts\Transaction as ContractsTransaction;

class Transaction implements ContractsTransaction
{
    protected const DEPOSIT = 'deposit';
    protected const WITHDRAW = 'withdraw';
    protected const BUSINESS = 'business';
    protected const PRIVATE = 'private';

    protected int $dateYear;

    protected int $dateWeek;

    protected int $userID;

    protected string $userType;

    protected string $operationType;

    protected float $amount;

    public function __construct(array $input)
    {
        $date = new DateTime($input[0]);
        // 'o' - consistently format year, in regards to week number
        $this->dateYear = (int) $date->format('o');
        $this->dateWeek = (int) $date->format('W');
        $this->userID = (int) $input[1] ?? -1;
        $this->userType = $input[2] ?? 'unknown';
        $this->operationType = $input[3] ?? 'unknown';
        $this->amount = (float) $input[4] ?? 0;
    }

    public function isDeposit(): bool
    {
        return $this->operationType === self::DEPOSIT;
    }

    public function isWithdraw(): bool
    {
        return $this->operationType === self::WITHDRAW;
    }

    public function isBusiness(): bool
    {
        return $this->userType === self::BUSINESS;
    }

    public function isPrivate(): bool
    {
        return $this->userType === self::PRIVATE;
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
