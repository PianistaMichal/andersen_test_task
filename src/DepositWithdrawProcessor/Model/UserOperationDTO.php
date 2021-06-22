<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use DateTime;

class UserOperationDTO
{
    private Datetime $createdAt;
    private int $userId;
    private UserType $userType;
    private DepositType $depositType;
    private float $operationAmount;
    private OperationCurrency $operationCurrency;

    public function __construct(
        DateTime $createdAt,
        int $userId,
        UserType $userType,
        DepositType $depositType,
        float $operationAmount,
        OperationCurrency $operationCurrency
    ) {
        $this->createdAt = $createdAt;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->depositType = $depositType;
        $this->operationAmount = $operationAmount;
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

    public function getOperationAmount(): float
    {
        return $this->operationAmount;
    }

    public function getOperationCurrency(): OperationCurrency
    {
        return $this->operationCurrency;
    }
}