<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Storage;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use DateTime;

interface UserOperationRepository
{
    /**
     * @return UserOperationDTO[]
     */
    public function findAllCreatedAtBetweenAndDepositTypeWithdrawAndUserId(
        DateTime $firstDate,
        DateTime $secondDate,
        int $userId
    ): array;

    public function saveUserOperation(UserOperationDTO $userOperationDTO): void;
}