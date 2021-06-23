<?php

declare(strict_types=1);

namespace App\SharedKernel;

class Math
{
    private static int $BASIC_SCALE_FOR_CALCULATIONS = 20;
    private int $scale;

    public function __construct(int $scale)
    {
        $this->scale = $scale;
    }

    public function add(string $leftOperand, string $rightOperand): string
    {
        return bcadd($leftOperand, $rightOperand, self::$BASIC_SCALE_FOR_CALCULATIONS);
    }

    public function multiply(string $leftOperand, string $rightOperand): string
    {
        return bcmul($leftOperand, $rightOperand, self::$BASIC_SCALE_FOR_CALCULATIONS);
    }

    public function divide(string $leftOperand, string $rightOperand): string
    {
        return bcdiv($leftOperand, $rightOperand, self::$BASIC_SCALE_FOR_CALCULATIONS);
    }

    public function sub(string $leftOperand, string $rightOperand): string
    {
        return bcsub($leftOperand, $rightOperand, self::$BASIC_SCALE_FOR_CALCULATIONS);
    }

    public function comp(string $leftOperand, string $rightOperand): int
    {
        return bccomp($leftOperand, $rightOperand, self::$BASIC_SCALE_FOR_CALCULATIONS);
    }

    /**
     * https://www.php.net/manual/en/function.round.php#114573.
     */
    public function round(string $number): string
    {
        return bcadd($number, '0', $this->scale);
    }
}
