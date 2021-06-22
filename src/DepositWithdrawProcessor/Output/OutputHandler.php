<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

interface OutputHandler
{
    public function addOutputData(string $value): void;
    public function flushDataToOutputStream(): void;
}