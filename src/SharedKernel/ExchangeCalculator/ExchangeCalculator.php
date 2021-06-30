<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

use App\DepositWithdrawProcessor\Enums\Currency;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;

interface ExchangeCalculator
{
    /**
     * @throws CannotGetExchangeRatesInformationException
     */
    public function getAmountFromCurrencyToBaseCurrency(string $currencyAmount, Currency $currencyFrom): string;

    /**
     * @throws CannotGetExchangeRatesInformationException
     */
    public function getAmountFromBaseCurrencyToGivenCurrency(string $currencyAmount, Currency $currencyTo): string;
}
