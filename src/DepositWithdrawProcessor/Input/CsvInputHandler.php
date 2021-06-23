<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Input\Exception\StreamOpenFailedException;
use App\DepositWithdrawProcessor\Model\Currency;
use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use App\SharedKernel\Number\ExchangeableNumberFactory;
use DateTime;

class CsvInputHandler implements InputHandler
{
    private ExchangeableNumberFactory $exchangeableNumberFactory;

    public function __construct(ExchangeableNumberFactory $exchangeableNumberFactory)
    {
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
    }

    public function getData(string $streamName): iterable
    {
        if (!file_exists($streamName)) {
            throw new StreamOpenFailedException(sprintf('File not found: %s', $streamName));
        }
        $handle = fopen($streamName, 'r');
        if ($handle === false) {
            throw new StreamOpenFailedException(sprintf('File cannot be open: %s', $streamName));
        }
        while (($row = fgetcsv($handle)) !== false) {
            yield new UserOperationDTO(
                new DateTime($row[0]),
                (int) $row[1],
                UserType::from(strtoupper($row[2])),
                DepositType::from(strtoupper($row[3])),
                $this->exchangeableNumberFactory->create($row[4], Currency::from(strtoupper($row[5]))),
                Currency::from(strtoupper($row[5]))
            );
        }
    }
}
