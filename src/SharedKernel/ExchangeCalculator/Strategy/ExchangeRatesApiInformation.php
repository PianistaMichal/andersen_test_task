<?php

declare(strict_types=1);

namespace App\SharedKernel\ExchangeCalculator\Strategy;

use App\DepositWithdrawProcessor\Model\Currency;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;
use Exception;
use GuzzleHttp\Client;

class ExchangeRatesApiInformation implements ExchangeRatesInformation
{
    private Client $client;
    private string $apiToken;
    private Currency $baseCurrency;

    public function __construct(Client $client, string $apiToken, Currency $baseCurrency)
    {
        $this->client = $client;
        $this->apiToken = $apiToken;
        $this->baseCurrency = $baseCurrency;
    }

    public function getExchangeConverseRatesForAllCurrencies(): array
    {
        $response = $this->client->get(
            'latest',
            [
                'query' => [
                    'access_key' => $this->apiToken,
                    'base' => $this->baseCurrency->getValue(),
                    'symbols' => implode(',', Currency::values()),
                ],
            ]
        );
        try {
            $responseParsed = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new CannotGetExchangeRatesInformationException($e->getMessage(), $e->getCode(), $e);
        }
        if (!isset($responseParsed['rates'])) {
            throw new CannotGetExchangeRatesInformationException(sprintf('Wrong format of response: %s', json_encode($responseParsed)));
        }
        $allRates = [];
        foreach (Currency::values() as $currency) {
            $value = 1;
            if ($currency !== $this->baseCurrency->getValue()) {
                $value = (string) $responseParsed['rates'][$currency->getValue()];
            }
            $allRates[$currency->getValue()] = $value;
        }

        return $allRates;
    }
}
