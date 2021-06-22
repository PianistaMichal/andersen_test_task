<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

use App\SharedKernel\Math;
use App\SharedKernel\Number\ExchangeableNumber;

class StandardOutputHandler implements OutputHandler
{
    private string $lines;
    private Math $math;

    public function __construct(Math $math)
    {
        $this->lines = "";
        $this->math = $math;
    }

    public function addOutputData(ExchangeableNumber $exchangeableNumber): void
    {
        $this->lines .= $this->math->round($exchangeableNumber->getCurrencyAmountInCurrentCurrency()) . "\n";
    }

    public function flushDataToOutputStream(): void
    {
        fwrite(STDOUT, $this->lines);
    }
}