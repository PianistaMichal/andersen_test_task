<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Storage;

use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use DateTime;

class InMemoryUserOperationRepository implements UserOperationRepository
{
    private array $userOperations;

    public function __construct()
    {
        $this->userOperations = [];
    }

    public function findAllCreatedAtBetweenAndDepositTypeWithdrawAndUserId(
        DateTime $firstDate,
        DateTime $secondDate,
        int $userId
    ): array {
        $userOperationsToReturn = [];
        if (isset($this->userOperations[$userId])) {
            /** @var UserOperationDTO $userOperation */
            foreach ($this->userOperations[$userId] as $userOperation) {
                if ($userOperation->getDepositType()->equals(DepositType::WITHDRAW()) && $userOperation->getCreatedAt(
                    ) >= $firstDate && $userOperation->getCreatedAt() <= $secondDate) {
                    $userOperationsToReturn[] = $userOperation;
                }
            }
        }

        return $userOperationsToReturn;
    }

    public function saveUserOperation(UserOperationDTO $userOperationDTO): void
    {
        if (!isset($this->userOperations[$userOperationDTO->getUserId()])) {
            $this->userOperations[$userOperationDTO->getUserId()] = [];
        }

        $this->userOperations[$userOperationDTO->getUserId()][] = $userOperationDTO;
    }
}
