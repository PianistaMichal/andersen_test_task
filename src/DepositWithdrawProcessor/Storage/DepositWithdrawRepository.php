<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Storage;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use DateTime;

interface DepositWithdrawRepository
{
    /**
     * @return UserOperationDTO[]
     */
    public function findAllCreatedAtBetweenAndDepositTypeWithdraw(DateTime $firstDate, DateTime $secondDate): array;
}