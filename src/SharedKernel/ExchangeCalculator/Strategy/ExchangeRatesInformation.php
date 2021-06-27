<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator\Strategy;

use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;

interface ExchangeRatesInformation
{
    /**
     * @throws CannotGetExchangeRatesInformationException
     */
    public function getExchangeConverseRatesForAllCurrencies(): array;
}
