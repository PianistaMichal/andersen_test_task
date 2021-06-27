<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeAndDepositTypeException;
use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;

class FeeFactory
{
    /**
     * @var FeeStrategy[]
     */
    private iterable $feeStrategies;

    /**
     * @param FeeStrategy[] $feeStrategies
     */
    public function __construct(iterable $feeStrategies)
    {
        $this->feeStrategies = $feeStrategies;
    }

    /**
     * @throws NoHandlerForUserTypeAndDepositTypeException
     */
    public function getFeeStrategyForUserTypeAndDepositType(UserType $userType, DepositType $depositType): FeeStrategy
    {
        foreach ($this->feeStrategies as $feeStrategy) {
            foreach ($feeStrategy->getWorkingOnDepositType() as $depositTypeWorkingOn) {
                foreach ($feeStrategy->getWorkingOnUserType() as $userTypeWorkingOn) {
                    if ($userType->equals($userTypeWorkingOn) && $depositType->equals($depositTypeWorkingOn)) {
                        return $feeStrategy;
                    }
                }
            }
        }

        throw new NoHandlerForUserTypeAndDepositTypeException($depositType, $userType);
    }
}
