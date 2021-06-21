<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator;

use App\DepositWithdrawProcessor\Model\InputElementDTO;

interface FeeCalculator
{
    public function calculateFeeForTransaction(InputElementDTO $inputElementDTO): float;
}