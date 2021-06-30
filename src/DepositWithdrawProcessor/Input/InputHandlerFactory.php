<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Input\Exception\StreamOpenFailedException;
use App\SharedKernel\Number\ExchangeableNumberFactory;

class InputHandlerFactory
{
    private ExchangeableNumberFactory $exchangeableNumberFactory;

    public function __construct(ExchangeableNumberFactory $exchangeableNumberFactory)
    {
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
    }

    /**
     * @throws StreamOpenFailedException
     */
    public function create(string $streamName): InputHandler
    {
        return new CsvInputHandler(
            $this->exchangeableNumberFactory,
            $streamName
        );
    }
}
