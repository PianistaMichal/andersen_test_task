<?php
declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

class CacheExchangeCalculatorDecorator implements ExchangeCalculator
{
    private array $exchanges;
    private ExchangeCalculator $exchangeCalculatorChild;


    public function __constructor(ExchangeCalculator $child) {
        $this->exchanges = [];
        $this->exchangeCalculatorChild = $child;
    }

    public function getExchangeRatioForCurrencies(string $firstCurrency, string $secondCurrency): float
    {
        if(!isset($this->exchanges[$firstCurrency])) {
            $this->exchanges[$firstCurrency] = [];
        }
        if (isset($this->exchanges[$firstCurrency][$secondCurrency])) {
            return $this->exchanges[$firstCurrency][$secondCurrency];
        }

        $this->exchanges[$firstCurrency][$secondCurrency] = $this->exchangeCalculatorChild->getExchangeRatioForCurrencies($firstCurrency, $secondCurrency);
    }
}