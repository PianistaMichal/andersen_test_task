<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Output;

class StandardOutputHandler implements OutputHandler
{
    private string $lines;

    public function __constructor()
    {
        $this->lines = "";
    }

    public function addOutputData(string $value): void
    {
        $this->lines .= $value . "\n";
    }

    public function flushDataToOutputStream(): void
    {
        fwrite(STDOUT, $this->lines);
    }
}