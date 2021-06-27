<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Input\Exception\CannotConvertDateTimeException;
use App\DepositWithdrawProcessor\Input\Exception\CannotParseToEnumException;
use App\DepositWithdrawProcessor\Input\Exception\StreamOpenFailedException;
use App\DepositWithdrawProcessor\Model\Currency;
use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use App\SharedKernel\Number\ExchangeableNumberFactory;
use DateTime;
use Exception;
use UnexpectedValueException;

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
        $handle = fopen($streamName, 'rb');
        if ($handle === false) {
            throw new StreamOpenFailedException(sprintf('File cannot be open: %s', $streamName));
        }
        while (($row = fgetcsv($handle)) !== false) {
            try {
                $datetime = new DateTime($row[0]);
            } catch (Exception $e) {
                throw new CannotConvertDateTimeException($e->getMessage(), $e->getCode(), $e);
            }
            try {
                $userType = UserType::from(strtoupper($row[2]));
                $depositType = DepositType::from(strtoupper($row[3]));
            } catch (UnexpectedValueException $e) {
                throw new CannotParseToEnumException($e->getMessage(), $e->getCode(), $e);
            }
            yield new UserOperationDTO(
                $datetime,
                (int) $row[1],
                $userType,
                $depositType,
                $this->exchangeableNumberFactory->create($row[4], Currency::from(strtoupper($row[5]))),
                Currency::from(strtoupper($row[5]))
            );
        }
    }
}
