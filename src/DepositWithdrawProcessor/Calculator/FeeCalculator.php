<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\SharedKernel\Number\ExchangeableNumber;

interface FeeCalculator
{
    public function calculateFeeForTransaction(UserOperationDTO $userOperationDTO): ExchangeableNumber;
}
