<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input\Exception;

use RuntimeException;

class CannotParseToEnumException extends RuntimeException implements InputException
{
}
