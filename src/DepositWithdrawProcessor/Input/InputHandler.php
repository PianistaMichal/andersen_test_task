<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;

interface InputHandler
{
    /**
     * @return UserOperationDTO[]
     */
    public function getData(string $streamName): iterable;
}
