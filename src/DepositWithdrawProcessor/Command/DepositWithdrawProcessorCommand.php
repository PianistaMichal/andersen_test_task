<?php
declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Command;

use App\DepositWithdrawProcessor\Input\InputHandler;
use App\DepositWithdrawProcessor\Output\OutputHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DepositWithdrawProcessorCommand extends Command {

    protected static $defaultName = 'app:deposit_withdraw_processor_command';

    private InputHandler $inputHandler;
    private OutputHandler $outputHandler;

    protected function configure(): void
    {
        $this
            ->setDescription('Processed input to handle deposit and withdraw operations and return fees');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        return Command::SUCCESS;
    }
}