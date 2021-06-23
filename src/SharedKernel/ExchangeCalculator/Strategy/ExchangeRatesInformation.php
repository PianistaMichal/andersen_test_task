<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator\Strategy;

interface ExchangeRatesInformation
{
    public function getExchangeConverseRatesForAllCurrencies(): array;
}
