<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command;

use App\DepositWithdrawProcessor\Calculator\BasicFeeAdapter;
use App\DepositWithdrawProcessor\Calculator\Exception\NoHandlerForUserTypeAndDepositTypeException;
use App\DepositWithdrawProcessor\Command\Validator\DepositWithdrawProcessorCommandValidator;
use App\DepositWithdrawProcessor\Command\Validator\Exception\ValidationException;
use App\DepositWithdrawProcessor\Input\Exception\InputException;
use App\DepositWithdrawProcessor\Input\InputHandlerFactory;
use App\DepositWithdrawProcessor\Output\OutputHandler;
use App\SharedKernel\ExchangeCalculator\Strategy\Exception\CannotGetExchangeRatesInformationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DepositWithdrawProcessorCommand extends Command
{
    protected static $defaultName = 'app:deposit_withdraw_processor_command';

    private InputHandlerFactory $inputHandlerFactory;
    private OutputHandler $outputHandler;
    private DepositWithdrawProcessorCommandValidator $commandValidator;
    private BasicFeeAdapter $feeAdapter;

    public function __construct(
        InputHandlerFactory $inputHandlerFactory,
        OutputHandler $outputHandler,
        DepositWithdrawProcessorCommandValidator $commandValidator,
        BasicFeeAdapter $feeAdapter
    ) {
        $this->inputHandlerFactory = $inputHandlerFactory;
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
        $iterator = $this->inputHandlerFactory->create($inputStreamPath);

        while ($iterator->valid()) {
            $element = null;
            try {
                $element = $iterator->current();
                $errors = $this->commandValidator->validate($element);
                if (count($errors) > 0) {
                    throw new ValidationException(implode(';', $errors));
                }
            } catch (InputException | ValidationException $e) {
                $output->writeln(sprintf('In row: %s, there is error: %s', $iterator->key(), $e->getMessage()));
                $element = null;
            }

            if ($element !== null) {
                try {
                    $feeToPay = $this->feeAdapter->calculateFeeForTransaction($element);
                    $this->outputHandler->addOutputData($feeToPay);
                } catch (CannotGetExchangeRatesInformationException | NoHandlerForUserTypeAndDepositTypeException $e) {
                    $output->writeln(sprintf('In row: %s, there is error: %s', $iterator->key(), $e->getMessage()));
                }
            }

            $iterator->next();
        }

        return Command::SUCCESS;
    }
}
