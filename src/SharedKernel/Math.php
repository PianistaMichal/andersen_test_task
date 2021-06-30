<?php

declare(strict_types=1);

namespace App\SharedKernel;

class Math
{
    private const BASIC_SCALE_FOR_CALCULATIONS = 20;
    private int $basicScaleForCalculations;
    private int $roundPrecision;

    public function __construct(int $roundPrecision)
    {
        $this->basicScaleForCalculations = $roundPrecision + self::BASIC_SCALE_FOR_CALCULATIONS;
        $this->roundPrecision = $roundPrecision;
    }

    public function add(string $leftOperand, string $rightOperand): string
    {
        return bcadd($leftOperand, $rightOperand, $this->basicScaleForCalculations);
    }

    public function multiply(string $leftOperand, string $rightOperand): string
    {
        return bcmul($leftOperand, $rightOperand, $this->basicScaleForCalculations);
    }

    public function divide(string $leftOperand, string $rightOperand): string
    {
        return bcdiv($leftOperand, $rightOperand, $this->basicScaleForCalculations);
    }

    public function sub(string $leftOperand, string $rightOperand): string
    {
        return bcsub($leftOperand, $rightOperand, $this->basicScaleForCalculations);
    }

    public function comp(string $leftOperand, string $rightOperand): int
    {
        return bccomp($leftOperand, $rightOperand, $this->basicScaleForCalculations);
    }
}
