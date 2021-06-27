<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Input\Exception\InputException;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;

interface InputHandler
{
    /**
     * @return UserOperationDTO[]
     *
     * @throws InputException
     */
    public function getData(string $streamName): iterable;
}
