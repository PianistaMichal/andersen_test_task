<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\SharedKernel\Number\ExchangeableNumber;

class WithdrawBusinessStrategy implements FeeStrategy
{
    private const WITHDRAW_BUSINESS_BASIC_CHARGE = '0.005';

    public function calculateFee(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        return $userOperationDTO->getExchangeableNumber()->multiply(self::WITHDRAW_BUSINESS_BASIC_CHARGE);
    }

    public function getWorkingOnDepositType(): array
    {
        return [
            DepositType::WITHDRAW(),
        ];
    }

    public function getWorkingOnUserType(): array
    {
        return [
            UserType::BUSINESS(),
        ];
    }
}
