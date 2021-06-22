<?php

declare(strict_types=1);

namespace App\SharedKernel\Number;

use App\DepositWithdrawProcessor\Model\Currency;
use App\SharedKernel\ExchangeCalculator\ExchangeCalculator;
use App\SharedKernel\Math;

class ExchangeableNumberFactory
{
    private ExchangeCalculator $exchangeCalculator;
    private Math $math;
    private Currency $baseCurrency;

    public function __construct(ExchangeCalculator $exchangeCalculator, Math $math, Currency $baseCurrency)
    {
        $this->exchangeCalculator = $exchangeCalculator;
        $this->math = $math;
        $this->baseCurrency = $baseCurrency;
    }

    public function create(string $currencyAmount, Currency $operationCurrency): ExchangeableNumber
    {
        return new ExchangeableNumber($this->exchangeCalculator, $this->math, $this->baseCurrency, $currencyAmount, $operationCurrency);
    }
}