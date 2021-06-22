<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Input\Exception\StreamOpenFailedException;
use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\OperationCurrency;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use DateTime;

class CsvInputHandler implements InputHandler
{
    public function getData(string $streamName): iterable
    {
        if (!file_exists($streamName)) {
            throw new StreamOpenFailedException(sprintf('File not found: %s', $streamName));
        }
        $handle = fopen($streamName, "r");
        if ($handle === false) {
            throw new StreamOpenFailedException(sprintf('File cannot be open: %s', $streamName));
        }
        $data = fgetcsv($handle);
        foreach ($data as $element) {
            yield new UserOperationDTO(
                new DateTime($element[0]),
                $element[1],
                UserType::from(strtoupper($element[2])),
                DepositType::from(strtoupper($element[3])),
                (float)$element[4],
                OperationCurrency::from(strtoupper($element[5]))
            );
        }
    }
}