<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeAndDepositTypeException;
use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;
use App\SharedKernel\Number\ExchangeableNumber;

class DepositStrategy implements FeeStrategy
{
    private const DEPOSIT_BASIC_CHARGE = '0.0003';

    /**
     * @throws CannotGetExchangeRatesInformationException
     * @throws NoHandlerForUserTypeAndDepositTypeException
     */
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
