<?php

declare(strict_types=1);

namespace App\SharedKernel;

use App\DepositWithdrawProcessor\Enums\Currency;

class RoundToDecimalsHelper
{
    private array $zeroDecimalPlacesRoundingCurrencies = [];
    private int $roundPrecision;

    /**
     * @param Currency[] $zeroDecimalPlacesRoundingCurrencies
     */
    public function __construct(int $roundPrecision, array $zeroDecimalPlacesRoundingCurrencies)
    {
        $this->roundPrecision = $roundPrecision;
        $this->zeroDecimalPlacesRoundingCurrencies = $zeroDecimalPlacesRoundingCurrencies;
    }

    /**
     * Should use this function only when saving or displaying. Otherwise use functions from above.
     *
     * https://stackoverflow.com/questions/8239600/rounding-up-to-the-second-decimal-place/8239620#comment67989595_8239620
     */
    public function round(string $number, Currency $currency): string
    {
        $precision = $this->getRoundPrecision($currency);
        $offset = 0.5;
        if ($precision !== 0) {
            $offset /= pow(10, $precision);
        }
        $ceil = (string) round((float) $number + $offset, $precision, PHP_ROUND_HALF_DOWN);

        return bcadd($ceil, '0', $precision);
    }

    private function getRoundPrecision(Currency $currency): int
    {
        $roundPrecision = $this->roundPrecision;
        foreach ($this->zeroDecimalPlacesRoundingCurrencies as $zeroDecimalPlacesRoundingCurrency) {
            if ($zeroDecimalPlacesRoundingCurrency->equals($currency)) {
                $roundPrecision = 0;
            }
        }

        return $roundPrecision;
    }
}
