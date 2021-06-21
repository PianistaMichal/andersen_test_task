<?php
declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator;

use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForDepositTypeException;
use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeException;
use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\OperationCurrency;
use App\DepositWithdrawProcessor\Model\UserType;
use App\DepositWithdrawProcessor\Storage\DepositWithdrawRepository;
use App\SharedKernel\ExchangeCalculator\ExchangeCalculator;

class BasicFeeCalculator implements FeeCalculator
{
    private const DEPOSIT_BASIC_CHARGE = 0.0003;
    private const WITHDRAW_PRIVATE_BASIC_CHARGE = 0.003;
    private const WITHDRAW_BUSINESS_BASIC_CHARGE = 0.005;
    private const WITHDRAW_PRIVATE_BASIC_THRESHOLD = 1000;

    private ExchangeCalculator $exchangeCalculator;
    private DepositWithdrawRepository $depositWithdrawRepository;

    public function __construct(ExchangeCalculator $exchangeCalculator, DepositWithdrawRepository $depositWithdrawRepository)
    {
        $this->exchangeCalculator = $exchangeCalculator;
        $this->depositWithdrawRepository = $depositWithdrawRepository;
    }

    public function calculateFeeForTransaction(UserOperationDTO $inputElementDTO): float
    {
        if($inputElementDTO->getDepositType()->equals(DepositType::WITHDRAW())) {
            return $this->calculateForDeposit($inputElementDTO);
        } else if($inputElementDTO->getDepositType()->equals(DepositType::DEPOSIT())) {
            return $this->calculateForWithdraw($inputElementDTO);
        }

        throw new NoHandlerForDepositTypeException($inputElementDTO->getDepositType());
    }

    private function calculateForWithdraw(UserOperationDTO $inputElementDTO): float {
        if ($inputElementDTO->getUserType()->equals(UserType::BUSINESS())) {
            return $inputElementDTO * self::WITHDRAW_BUSINESS_BASIC_CHARGE;
        } elseif ($inputElementDTO->getUserType()->equals(UserType::PRIVATE())) {
            return $this->calculateForWithdrawAndPrivate($inputElementDTO);
        }

        throw new NoHandlerForUserTypeException($inputElementDTO->getUserType());
    }

    private function calculateForDeposit(UserOperationDTO $inputElementDTO): float {
        return $inputElementDTO * self::DEPOSIT_BASIC_CHARGE;
    }

    private function calculateForWithdrawAndPrivate(UserOperationDTO $inputElementDTO): float {
        $userOperations = $this->depositWithdrawRepository->findAllCreatedAtBetweenAndDepositTypeWithdraw();

        $moneyWithdrawnFromPrevious = 0;
        foreach ($userOperations as $userOperation) {
            $moneyWithdrawnFromPrevious += $userOperation->getOperationAmount() * $this->exchangeCalculator->getExchangeRatioForCurrencies($userOperation->getOperationCurrency(), OperationCurrency::EUR());
        }

        $commissionFee = 0;
        if(count($userOperations) < 3) {
            $allMoneyWithdrawn = $moneyWithdrawnFromPrevious + $inputElementDTO->getOperationAmount();
            /**
             * For example:
             * All money withdrawn was 1300 EUR
             * Current operation took 400 EUR
             * THRESHOLD is 1000 EUR
             * Money withdrawn that are crossing threshold are 300 EUR
             * Is less than current operation so we take all money that are crossing threshold to fee
             * If current operation is 200 EUR and money crossing threshold is still 300 EUR
             * We fee all money from current operation
             */
            if($allMoneyWithdrawn > self::WITHDRAW_PRIVATE_BASIC_THRESHOLD) {
                $moneyWithdrawnCrossingThreshold = $allMoneyWithdrawn - self::WITHDRAW_PRIVATE_BASIC_THRESHOLD;
                $moneyToGetCommissionFee = $moneyWithdrawnCrossingThreshold > $inputElementDTO->getOperationAmount() ? $inputElementDTO->getOperationAmount() : $moneyWithdrawnCrossingThreshold;
                $commissionFee = $moneyToGetCommissionFee * self::WITHDRAW_PRIVATE_BASIC_CHARGE;
            }
        } else {
            $commissionFee = $inputElementDTO->getOperationAmount() * self::WITHDRAW_PRIVATE_BASIC_CHARGE;
        }

        return $commissionFee;
    }
}