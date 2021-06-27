<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use App\SharedKernel\Number\ExchangeableNumber;

interface FeeStrategy
{
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
