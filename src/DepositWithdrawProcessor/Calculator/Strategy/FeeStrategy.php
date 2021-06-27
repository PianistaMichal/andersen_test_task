<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
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
