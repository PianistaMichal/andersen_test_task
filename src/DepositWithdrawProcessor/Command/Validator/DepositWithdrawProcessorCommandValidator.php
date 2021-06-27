<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command\Validator;

use App\DepositWithdrawProcessor\Model\Currency;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\SharedKernel\Number\ExchangeableNumberFactory;

class DepositWithdrawProcessorCommandValidator
{
    private ExchangeableNumberFactory $exchangeableNumberFactory;
    private Currency $baseCurrency;

    public function __construct(ExchangeableNumberFactory $exchangeableNumberFactory, Currency $baseCurrency)
    {
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * @return string[]
     */
    public function validate(UserOperationDTO $userOperationDTO): array
    {
        $errors = [];
        if (!$userOperationDTO->getExchangeableNumber()->greaterThanEqual(
            $this->exchangeableNumberFactory->create('0', $this->baseCurrency)
        )) {
            $errors[] = 'Operation currency amount is lower than 0';
        }

        return $errors;
    }
}
