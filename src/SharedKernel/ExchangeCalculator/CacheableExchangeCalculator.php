<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

use App\DepositWithdrawProcessor\Model\Currency;
use App\SharedKernel\ExchangeCalculator\Strategy\ExchangeRatesInformation;
use App\SharedKernel\Math;

class CacheableExchangeCalculator implements ExchangeCalculator
{
    private array $exchangesCache;
    private ExchangeRatesInformation $exchangeRatesInformation;
    private Math $math;

    public function __construct(ExchangeRatesInformation $exchangeRatesInformation, Math $math)
    {
        $this->exchangeRatesInformation = $exchangeRatesInformation;
        $this->math = $math;
        $this->exchangesCache = $this->exchangeRatesInformation->getExchangeConverseRatesForAllCurrencies();
    }

    public function getAmountFromCurrencyToBaseCurrency(
        string $currencyAmount, Currency $currencyFrom
    ): string {
        return $this->math->multiply($this->math->divide('1', $this->exchangesCache[$currencyFrom->getValue()]), $currencyAmount);
    }

    public function getAmountFromBaseCurrencyToGivenCurrency(string $currencyAmount, Currency $currencyTo): string
    {
        return $this->math->multiply($this->exchangesCache[$currencyTo->getValue()], $currencyAmount);
    }
}