<?php
declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

use App\DepositWithdrawProcessor\Model\OperationCurrency;

class CacheExchangeCalculatorDecorator implements ExchangeCalculator
{
    private array $exchangesCache;
    private ExchangeCalculator $exchangeCalculatorChild;

    public function __constructor(ExchangeCalculator $child) {
        $this->exchangesCache = [];
        $this->exchangeCalculatorChild = $child;
    }

    public function getExchangeRatioForCurrencies(OperationCurrency $firstCurrency, OperationCurrency $secondCurrency): float
    {
        if(!isset($this->exchangesCache[$firstCurrency->getValue()])) {
            $this->exchangesCache[$firstCurrency->getValue()] = [];
        }
        if (!isset($this->exchangesCache[$firstCurrency->getValue()][$secondCurrency->getValue()])) {
            $this->exchangesCache[$firstCurrency->getValue()][$secondCurrency->getValue()] = $this->exchangeCalculatorChild->getExchangeRatioForCurrencies($firstCurrency->getValue(), $secondCurrency->getValue());
        }

        return $this->exchangesCache[$firstCurrency->getValue()][$secondCurrency->getValue()];
    }
}