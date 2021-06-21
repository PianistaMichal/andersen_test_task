<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;

interface OutputHandler
{
    public function addOutputData(string $value): void;
    public function flushDataToOutputStream(): void;
}