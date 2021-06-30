<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

use App\SharedKernel\Number\ExchangeableNumber;
use App\SharedKernel\RoundToDecimalsHelper;

class StandardOutputHandler implements OutputHandler
{
    private RoundToDecimalsHelper $roundToDecimalsHelper;

    public function __construct(RoundToDecimalsHelper $roundToDecimalsHelper)
    {
        $this->roundToDecimalsHelper = $roundToDecimalsHelper;
    }

    public function addOutputData(ExchangeableNumber $exchangeableNumber): void
    {
        fwrite(
            STDOUT,
            $this->roundToDecimalsHelper->round(
                $exchangeableNumber->getCurrencyAmountInCurrentCurrency(),
                $exchangeableNumber->getCurrentCurrency()
            )."\n"
        );
    }
}
