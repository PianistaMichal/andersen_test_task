<?php

declare(strict_types=1);

namespace App\SharedKernel;

class Math
{
    private const BASIC_SCALE_FOR_CALCULATIONS = 20;
    private int $basicScaleForCalculations;
    private int $scale;

    public function __construct(int $scale)
    {
        $this->basicScaleForCalculations = $scale + self::BASIC_SCALE_FOR_CALCULATIONS;
        $this->scale = $scale;
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

    /**
     * Should use this function only when saving or displaying. Otherwise use functions from above
     *
     * https://stackoverflow.com/questions/8239600/rounding-up-to-the-second-decimal-place/8239620#comment67989595_8239620
     */
    public function round(string $number): string
    {
        $offset = 0.5;
        if ($this->scale !== 0)
            $offset /= pow(10, $this->scale);
        $ceil = (string) round((float)$number + $offset, $this->scale, PHP_ROUND_HALF_DOWN);

        return bcadd($ceil, '0', $this->scale);
    }
}
