<?php
declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command\Validator;

use App\DepositWithdrawProcessor\Model\UserOperationDTO;

class DepositWithdrawProcessorCommandValidator
{
    /**
     * @param UserOperationDTO $inputElementDTO
     * @return string[]
     */
    public function validate(UserOperationDTO $inputElementDTO): array {

    }
}