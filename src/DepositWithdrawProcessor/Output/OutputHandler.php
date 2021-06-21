<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

use App\DepositWithdrawProcessor\Model\InputElementDTO;

interface OutputHandler
{
    /**
     * @param InputElementDTO[] $inputElements
     */
    public function outputData(array $inputElements): void;
}