<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;

interface FeeCalculator
{
    public function calculateFeeForTransaction(UserOperationDTO $inputElementDTO): float;
}