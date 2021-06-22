<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

use App\SharedKernel\Number\ExchangeableNumber;

interface OutputHandler
{
    public function addOutputData(ExchangeableNumber $exchangeableNumber): void;

    public function flushDataToOutputStream(): void;
}