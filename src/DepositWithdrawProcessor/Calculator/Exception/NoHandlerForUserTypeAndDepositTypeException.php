<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Exception;

use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;
use RuntimeException;

class NoHandlerForUserTypeAndDepositTypeException extends RuntimeException
{
    public function __construct(DepositType $depositType, UserType $userType)
    {
        parent::__construct(
            sprintf(
                "There's no handling for such deposit type as: %s and user type: %s",
                $depositType->getValue(),
                $userType->getValue()
            )
        );
    }
}
