<?php

declare(strict_types=1);

namespace App\SharedKernel\Number;

use App\DepositWithdrawProcessor\Model\Currency;
use App\SharedKernel\ExchangeCalculator\ExchangeCalculator;
use App\SharedKernel\Math;

class ExchangeableNumber
{
    private ExchangeCalculator $exchangeCalculator;
    private Math $math;
    private Currency $baseCurrency;
    private string $currencyAmount;
    private Currency $currency;

    public function __construct(ExchangeCalculator $exchangeCalculator, Math $math, Currency $baseCurrency, string $currencyAmount, Currency $currency)
    {
        $this->exchangeCalculator = $exchangeCalculator;
        $this->math = $math;
        $this->baseCurrency = $baseCurrency;
        $this->currencyAmount = $currencyAmount;
        $this->currency = $currency;
    }

    public function add(ExchangeableNumber $exchangeableNumber): ExchangeableNumber
    {
        return new $this($this->exchangeCalculator, $this->math, $this->baseCurrency, $this->math->add($this->getCurrencyAmountInBaseCurrency(), $exchangeableNumber->getCurrencyAmountInBaseCurrency()), $this->baseCurrency);
    }

    public function multiply(string $value): ExchangeableNumber
    {
        return new $this($this->exchangeCalculator, $this->math, $this->baseCurrency, $this->math->multiply($this->getCurrencyAmountInCurrentCurrency(), $value), $this->currency);
    }

    public function divide(string $value): ExchangeableNumber
    {
        return new $this($this->exchangeCalculator, $this->math, $this->baseCurrency, $this->math->divide($this->getCurrencyAmountInCurrentCurrency(), $value), $this->currency);
    }

    public function sub(ExchangeableNumber $exchangeableNumber): ExchangeableNumber
    {
        return new $this($this->exchangeCalculator, $this->math, $this->baseCurrency, $this->math->sub($this->getCurrencyAmountInBaseCurrency(), $exchangeableNumber->getCurrencyAmountInBaseCurrency()), $this->baseCurrency);
    }

    public function greaterThan(ExchangeableNumber $exchangeableNumber): bool
    {
        return $this->math->comp($this->getCurrencyAmountInBaseCurrency(), $exchangeableNumber->getCurrencyAmountInBaseCurrency()) === 1;
    }

    public function getCurrencyAmountInBaseCurrency(): string
    {
        return $this->exchangeCalculator->getAmountFromCurrencyToBaseCurrency($this->currencyAmount, $this->currency);
    }

    public function getCurrencyAmountInGivenCurrency(Currency $givenCurrency): string
    {
        if($givenCurrency->equals($this->currency)) {
            return $this->currencyAmount;
        }

        return $this->exchangeCalculator->getAmountFromBaseCurrencyToGivenCurrency($this->getCurrencyAmountInBaseCurrency(), $givenCurrency);
    }

    public function getCurrencyAmountInCurrentCurrency(): string
    {
        return $this->currencyAmount;
    }
}