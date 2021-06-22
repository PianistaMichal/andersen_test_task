<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator;

use App\DepositWithdrawProcessor\Model\OperationCurrency;

class ApiExchangeCalculator implements ExchangeCalculator
{

    public function getExchangeRatioForCurrencies(
        OperationCurrency $firstCurrency,
        OperationCurrency $secondCurrency
    ): float {
    }
}