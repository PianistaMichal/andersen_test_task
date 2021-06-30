<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Calculator;

use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeAndDepositTypeException;
use App\DepositWithdrawProcessor\Calculator\Strategy\FeeFactory;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\DepositWithdrawProcessor\Storage\UserOperationRepository;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;
use App\SharedKernel\Number\ExchangeableNumber;
use App\SharedKernel\Number\ExchangeableNumberFactory;

class BasicFeeAdapter
{
    private FeeFactory $feeFactory;
    private UserOperationRepository $depositWithdrawRepository;
    private ExchangeableNumberFactory $exchangeableNumberFactory;

    public function __construct(
        FeeFactory $feeFactory,
        UserOperationRepository $depositWithdrawRepository,
        ExchangeableNumberFactory $exchangeableNumberFactory
    ) {
        $this->feeFactory = $feeFactory;
        $this->depositWithdrawRepository = $depositWithdrawRepository;
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
    }

    /**
     * @throws CannotGetExchangeRatesInformationException
     * @throws NoHandlerForUserTypeAndDepositTypeException
     */
    public function calculateFeeForTransaction(UserOperationDTO $userOperationDTO): ExchangeableNumber
    {
        try {
            $feeStrategy = $this->feeFactory->getFeeStrategyForUserTypeAndDepositType(
                $userOperationDTO->getUserType(),
                $userOperationDTO->getDepositType()
            );

            $fee = $feeStrategy->calculateFee($userOperationDTO);

            return $this->exchangeableNumberFactory->create(
                $fee->getCurrencyAmountInGivenCurrency($userOperationDTO->getOperationCurrency()),
                $userOperationDTO->getOperationCurrency()
            );
        } catch (CannotGetExchangeRatesInformationException | NoHandlerForUserTypeAndDepositTypeException $exception) {
            throw $exception;
        } finally {
            $this->depositWithdrawRepository->saveUserOperation($userOperationDTO);
        }
    }
}
