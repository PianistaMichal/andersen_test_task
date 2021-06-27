<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command;

use App\DepositWithdrawProcessor\Calculator\BasicFeeAdapter;
use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeAndDepositTypeException;
use App\DepositWithdrawProcessor\Command\Validator\DepositWithdrawProcessorCommandValidator;
use App\DepositWithdrawProcessor\Command\Validator\Exception\ValidationException;
use App\DepositWithdrawProcessor\Input\Exception\InputException;
use App\DepositWithdrawProcessor\Input\InputHandler;
use App\DepositWithdrawProcessor\Output\OutputHandler;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DepositWithdrawProcessorCommand extends Command
{
    protected static $defaultName = 'app:deposit_withdraw_processor_command';

    private InputHandler $inputHandler;
    private OutputHandler $outputHandler;
    private DepositWithdrawProcessorCommandValidator $commandValidator;
    private BasicFeeAdapter $feeAdapter;

    public function __construct(
        InputHandler $inputHandler,
        OutputHandler $outputHandler,
        DepositWithdrawProcessorCommandValidator $commandValidator,
        BasicFeeAdapter $feeAdapter
    ) {
        $this->inputHandler = $inputHandler;
        $this->outputHandler = $outputHandler;
        $this->commandValidator = $commandValidator;
        $this->feeAdapter = $feeAdapter;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Processed input to handle deposit and withdraw operations and return fees')
            ->addArgument('streamPath', InputArgument::REQUIRED, 'Stream path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputStreamPath = $input->getArgument('streamPath');
        $inputElements = [];
        try {
            foreach ($this->inputHandler->getData($inputStreamPath) as $id => $element) {
                $errors = $this->commandValidator->validate($element);
                if (count($errors) > 0) {
                    throw new ValidationException($id, $element->getUserId(), implode(';', $errors));
                }
                $inputElements[] = $element;
            }
        } catch (InputException | ValidationException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        foreach ($inputElements as $element) {
            try {
                $feeToPay = $this->feeAdapter->calculateFeeForTransaction($element);
                $this->outputHandler->addOutputData($feeToPay);
            } catch (CannotGetExchangeRatesInformationException | NoHandlerForUserTypeAndDepositTypeException $exception) {
                $output->writeln($exception->getMessage());

                return Command::FAILURE;
            }
        }
        $this->outputHandler->flushDataToOutputStream();

        return Command::SUCCESS;
    }
}
