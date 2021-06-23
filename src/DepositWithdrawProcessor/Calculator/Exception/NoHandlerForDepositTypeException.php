<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Exception;

use App\DepositWithdrawProcessor\Model\DepositType;
use RuntimeException;
use Throwable;

class NoHandlerForDepositTypeException extends RuntimeException
{
    public function __construct(DepositType $depositType, Throwable $previous = null)
    {
        parent::__construct(
            sprintf("There's no handling for such deposit type as: %s", $depositType->getValue()),
            0,
            $previous
        );
    }
}
