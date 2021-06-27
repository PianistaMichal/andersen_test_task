<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input\Exception;

use RuntimeException;

class CannotConvertDateTimeException extends RuntimeException implements InputException
{
}
