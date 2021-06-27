<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

use App\DepositWithdrawProcessor\Enums\Currency;
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
        $this->exchangesCache = [];
    }

    public function getAmountFromCurrencyToBaseCurrency(
        string $currencyAmount,
        Currency $currencyFrom
    ): string {
        return $this->math->multiply(
            $this->math->divide('1', $this->getExchangeConverseRates($currencyFrom)),
            $currencyAmount
        );
    }

    private function getExchangeConverseRates(Currency $currencyTo): string
    {
        if (empty($this->exchangesCache)) {
            $this->exchangesCache = $this->exchangeRatesInformation->getExchangeConverseRatesForAllCurrencies();
        }

        return $this->exchangesCache[$currencyTo->getValue()];
    }

    public function getAmountFromBaseCurrencyToGivenCurrency(string $currencyAmount, Currency $currencyTo): string
    {
        return $this->math->multiply($this->getExchangeConverseRates($currencyTo), $currencyAmount);
    }
}
