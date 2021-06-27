<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator\Strategy;

use App\DepositWithdrawProcessor\Model\Currency;
use App\DepositWithdrawProcessor\Model\DepositType;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Model\UserType;
use App\DepositWithdrawProcessor\Storage\UserOperationRepository;
use App\SharedKernel\Number\ExchangeableNumber;
use App\SharedKernel\Number\ExchangeableNumberFactory;

class WithdrawPrivateStrategy implements FeeStrategy
{
    private const WITHDRAW_PRIVATE_BASIC_CHARGE = '0.003';
    private const WITHDRAW_PRIVATE_BASIC_THRESHOLD = '1000';

    private UserOperationRepository $depositWithdrawRepository;
    private ExchangeableNumberFactory $exchangeableNumberFactory;
    private Currency $baseCurrency;

    public function __construct(
        UserOperationRepository $depositWithdrawRepository,
        ExchangeableNumberFactory $exchangeableNumberFactory,
        Currency $baseCurrency
    ) {
        $this->depositWithdrawRepository = $depositWithdrawRepository;
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
        $this->baseCurrency = $baseCurrency;
    }

    public function calculateFee(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        $userOperations = $this->depositWithdrawRepository->findAllCreatedAtBetweenAndDepositTypeWithdrawAndUserId(
            $userOperationDTO->getCreatedAt()->modify('tomorrow')->modify('previous monday'),
            $userOperationDTO->getCreatedAt()->modify('yesterday')->modify('next sunday'),
            $userOperationDTO->getUserId()
        );

        $allMoneyWithdrawnFromThisWeek = $this->exchangeableNumberFactory->create('0', Currency::EUR());
        foreach ($userOperations as $userOperation) {
            $allMoneyWithdrawnFromThisWeek = $allMoneyWithdrawnFromThisWeek->add(
                $userOperation->getExchangeableNumber()
            );
        }
        $allMoneyWithdrawnFromThisWeek = $allMoneyWithdrawnFromThisWeek->add(
            $userOperationDTO->getExchangeableNumber()
        );

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
             * We fee all money from current operation.
             */
            $withdrawBasicThreshold = $this->exchangeableNumberFactory->create(
                self::WITHDRAW_PRIVATE_BASIC_THRESHOLD,
                $this->baseCurrency
            );
            if ($allMoneyWithdrawnFromThisWeek->greaterThan(
                $this->exchangeableNumberFactory->create(self::WITHDRAW_PRIVATE_BASIC_THRESHOLD, Currency::EUR())
            )) {
                $moneyWithdrawnCrossingThreshold = $allMoneyWithdrawnFromThisWeek->sub($withdrawBasicThreshold);
                $commissionFee = $moneyWithdrawnCrossingThreshold->greaterThan(
                    $userOperationDTO->getExchangeableNumber()
                ) ? $userOperationDTO->getExchangeableNumber() : $moneyWithdrawnCrossingThreshold;
                $commissionFee = $commissionFee->multiply(self::WITHDRAW_PRIVATE_BASIC_CHARGE);
            }
        } else {
            $commissionFee = $userOperationDTO->getExchangeableNumber()->multiply(self::WITHDRAW_PRIVATE_BASIC_CHARGE);
        }

        return $commissionFee;
    }

    public function getWorkingOnDepositType(): array
    {
        return [
            DepositType::WITHDRAW(),
        ];
    }

    public function getWorkingOnUserType(): array
    {
        return [
            UserType::PRIVATE(),
        ];
    }
}
