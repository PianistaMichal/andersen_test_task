<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Input\Exception\CannotConvertDateTimeException;
use App\DepositWithdrawProcessor\Input\Exception\CannotParseToEnumException;
use App\DepositWithdrawProcessor\Input\Exception\StreamOpenFailedException;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use Iterator;

interface InputHandler extends Iterator
{
    /**
     * @throws CannotConvertDateTimeException
     * @throws CannotParseToEnumException
     */
    public function current(): UserOperationDTO;

    public function next(): void;

    public function key(): int;

    public function valid(): bool;

    /**
     * @throws StreamOpenFailedException
     */
    public function rewind(): void;
}
