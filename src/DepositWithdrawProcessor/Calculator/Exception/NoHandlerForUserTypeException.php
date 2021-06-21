<?php
declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Exception;

use App\DepositWithdrawProcessor\Model\UserType;
use RuntimeException;
use Throwable;

class NoHandlerForUserTypeException extends RuntimeException
{
    public function __construct(UserType $userType, Throwable $previous = null) {
        parent::__construct(sprintf("There's no handling for such user type as: %s", $userType->getValue()), 0, $previous);
    }
}