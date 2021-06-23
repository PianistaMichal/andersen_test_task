<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use App\SharedKernel\Number\ExchangeableNumber;
use DateTime;

class UserOperationDTO
{
    private Datetime $createdAt;
    private int $userId;
    private UserType $userType;
    private DepositType $depositType;
    private ExchangeableNumber $exchangeableNumber;
    private Currency $operationCurrency;

    public function __construct(
        DateTime $createdAt,
        int $userId,
        UserType $userType,
        DepositType $depositType,
        ExchangeableNumber $exchangeableNumber,
        Currency $operationCurrency
    ) {
        $this->createdAt = $createdAt;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->depositType = $depositType;
        $this->exchangeableNumber = $exchangeableNumber;
        $this->operationCurrency = $operationCurrency;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserType(): UserType
    {
        return $this->userType;
    }

    public function getDepositType(): DepositType
    {
        return $this->depositType;
    }

    public function getExchangeableNumber(): ExchangeableNumber
    {
        return $this->exchangeableNumber;
    }

    public function getOperationCurrency(): Currency
    {
        return $this->operationCurrency;
    }
}
