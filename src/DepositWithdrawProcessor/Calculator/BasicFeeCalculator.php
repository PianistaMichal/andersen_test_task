<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator;

use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForDepositTypeException;
use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeException;
use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\Currency;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use App\DepositWithdrawProcessor\Storage\UserOperationRepository;
use App\SharedKernel\Number\ExchangeableNumber;
use App\SharedKernel\Number\ExchangeableNumberFactory;

class BasicFeeCalculator implements FeeCalculator
{
    private const DEPOSIT_BASIC_CHARGE = '0.0003';
    private const WITHDRAW_PRIVATE_BASIC_CHARGE = '0.003';
    private const WITHDRAW_BUSINESS_BASIC_CHARGE = '0.005';
    private const WITHDRAW_PRIVATE_BASIC_THRESHOLD = '1000';

    private UserOperationRepository $depositWithdrawRepository;
    private ExchangeableNumberFactory $exchangeableNumberFactory;

    public function __construct(
        UserOperationRepository $depositWithdrawRepository,
        ExchangeableNumberFactory $exchangeableNumberFactory
    ) {
        $this->depositWithdrawRepository = $depositWithdrawRepository;
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
    }

    public function calculateFeeForTransaction(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        $feeForTransactions = null;
        if ($userOperationDTO->getDepositType()->equals(DepositType::WITHDRAW())) {
            $feeForTransactions = $this->calculateForWithdraw($userOperationDTO);
        } elseif ($userOperationDTO->getDepositType()->equals(DepositType::DEPOSIT())) {
            $feeForTransactions = $this->calculateForDeposit($userOperationDTO);
        }
        $this->depositWithdrawRepository->saveUserOperation($userOperationDTO);
        if($feeForTransactions !== null) {
            return $this->exchangeableNumberFactory->create($feeForTransactions->getCurrencyAmountInGivenCurrency($userOperationDTO->getOperationCurrency()), $userOperationDTO->getOperationCurrency());
        }

        throw new NoHandlerForDepositTypeException($userOperationDTO->getDepositType());
    }

    private function calculateForDeposit(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        return $userOperationDTO->getExchangeableNumber()->multiply(self::DEPOSIT_BASIC_CHARGE);
    }

    private function calculateForWithdraw(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        if ($userOperationDTO->getUserType()->equals(UserType::BUSINESS())) {
            return $userOperationDTO->getExchangeableNumber()->multiply(self::WITHDRAW_BUSINESS_BASIC_CHARGE);
        } elseif ($userOperationDTO->getUserType()->equals(UserType::PRIVATE())) {
            return $this->calculateForWithdrawAndPrivate($userOperationDTO);
        }

        throw new NoHandlerForUserTypeException($userOperationDTO->getUserType());
    }

    private function calculateForWithdrawAndPrivate(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        $userOperations = $this->depositWithdrawRepository->findAllCreatedAtBetweenAndDepositTypeWithdrawAndUserId(
            $userOperationDTO->getCreatedAt()->modify('previous monday'),
            $userOperationDTO->getCreatedAt()->modify('next sunday'),
            $userOperationDTO->getUserId()
        );

        $allMoneyWithdrawnFromThisWeek = $this->exchangeableNumberFactory->create('0', Currency::EUR());
        foreach ($userOperations as $userOperation) {
            $allMoneyWithdrawnFromThisWeek = $allMoneyWithdrawnFromThisWeek->add($userOperation->getExchangeableNumber());
        }
        $allMoneyWithdrawnFromThisWeek = $allMoneyWithdrawnFromThisWeek->add($userOperationDTO->getExchangeableNumber());

        $commissionFee = $this->exchangeableNumberFactory->create('0', $userOperationDTO->getOperationCurrency());
        if (count($userOperations) < 3) {
            /**
             * For example:
             * All money withdrawn was 1300 EUR
             * Current operation took 400 EUR
             * THRESHOLD is 1000 EUR
             * Money withdrawn that are crossing threshold are 300 EUR
             * Is less than current operation so we take all money that are crossing threshold to fee
             * If current operation is 200 EUR and money crossing threshold is 300 EUR
             * We fee all money from current operation
             */
            $withdrawBasicThreshold = $this->exchangeableNumberFactory->create(self::WITHDRAW_PRIVATE_BASIC_THRESHOLD, Currency::EUR());
            if ($allMoneyWithdrawnFromThisWeek->greaterThan($this->exchangeableNumberFactory->create(self::WITHDRAW_PRIVATE_BASIC_THRESHOLD, Currency::EUR()))) {
                $moneyWithdrawnCrossingThreshold = $allMoneyWithdrawnFromThisWeek->sub($withdrawBasicThreshold);
                $commissionFee = $moneyWithdrawnCrossingThreshold->greaterThan($userOperationDTO->getExchangeableNumber()) ? $userOperationDTO->getExchangeableNumber() : $moneyWithdrawnCrossingThreshold;
                $commissionFee = $commissionFee->multiply(self::WITHDRAW_PRIVATE_BASIC_CHARGE);
            }
        } else {
            $commissionFee = $userOperationDTO->getExchangeableNumber()->multiply(self::WITHDRAW_PRIVATE_BASIC_CHARGE);
        }
        return $commissionFee;
    }
}