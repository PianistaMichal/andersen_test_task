<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use App\SharedKernel\Number\ExchangeableNumber;

class DepositStrategy implements FeeStrategy
{
    private const DEPOSIT_BASIC_CHARGE = '0.0003';

    public function calculateFee(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        return $userOperationDTO->getExchangeableNumber()->multiply(self::DEPOSIT_BASIC_CHARGE);
    }

    public function getWorkingOnDepositType(): array
    {
        return [
            DepositType::DEPOSIT(),
        ];
    }

    public function getWorkingOnUserType(): array
    {
        return [
            UserType::PRIVATE(),
            UserType::BUSINESS(),
        ];
    }
}