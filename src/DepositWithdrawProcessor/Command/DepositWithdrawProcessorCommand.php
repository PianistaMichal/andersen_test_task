<?php
declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command;

use App\DepositWithdrawProcessor\Calculator\FeeCalculator;
use App\DepositWithdrawProcessor\Command\Validator\DepositWithdrawProcessorCommandValidator;
use App\DepositWithdrawProcessor\Command\Validator\ValidationException;
use App\DepositWithdrawProcessor\Input\InputHandler;
use App\DepositWithdrawProcessor\Model\OperationCurrency;
use App\DepositWithdrawProcessor\Output\OutputHandler;
use App\SharedKernel\ExchangeCalculator\ExchangeCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DepositWithdrawProcessorCommand extends Command {

    protected static $defaultName = 'app:deposit_withdraw_processor_command';

    private InputHandler $inputHandler;
    private OutputHandler $outputHandler;
    private DepositWithdrawProcessorCommandValidator $commandValidator;
    private FeeCalculator $feeCalculator;

    protected function configure(): void
    {
        $this
            ->setDescription('Processed input to handle deposit and withdraw operations and return fees');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //TODO: validation in correct way
        $inputElements = [];
        foreach ($this->inputHandler->getData() as $id => $element) {
            $errors = $this->commandValidator->validate($element);
            if (count($errors) > 0) {
                throw new ValidationException($id, $element->getUserId(), implode(";", $errors));
            }
            $inputElements[] = $element;
        }

        foreach ($inputElements as $element) {
            $feeToPay = $this->feeCalculator->calculateFeeForTransaction($element);
            $this->outputHandler->addOutputData((string)$feeToPay);
        }
        $this->outputHandler->flushDataToOutputStream();

        return Command::SUCCESS;
    }
}