<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Model\InputElementDTO;

interface InputHandler
{
    /**
     * @return InputElementDTO[]
     */
    public function getData(): array;
}