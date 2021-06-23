<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

use App\DepositWithdrawProcessor\Model\Currency;

interface ExchangeCalculator
{
    public function getAmountFromCurrencyToBaseCurrency(string $currencyAmount, Currency $currencyFrom): string;

    public function getAmountFromBaseCurrencyToGivenCurrency(string $currencyAmount, Currency $currencyTo): string;
}
