<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command\Validator\Exception;

use RuntimeException;
use Throwable;

class ValidationException extends RuntimeException
{
    public function __construct(int $rowId, int $userId, string $errorMessage, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'There is error in row number: %d for user id: %d. Error message: %s',
                $rowId,
                $userId,
                $errorMessage
            ),
            $previous
        );
    }
}
