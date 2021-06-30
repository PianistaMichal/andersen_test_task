<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeAndDepositTypeException;
use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;
use App\SharedKernel\Number\ExchangeableNumber;

interface FeeStrategy
{
    /**
     * @throws CannotGetExchangeRatesInformationException
     * @throws NoHandlerForUserTypeAndDepositTypeException
     */
    public function calculateFee(UserOperationDTO $userOperationDTO): ExchangeableNumber;

    /**
     * @return DepositType[]
     */
    public function getWorkingOnDepositType(): array;

    /**
     * @return UserType[]
     */
    public function getWorkingOnUserType(): array;
}
