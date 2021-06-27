<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\SharedKernel\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddTwoNumbers(): void
    {
        $roundPrecision = 2;
        $math = new Math($roundPrecision);
        self::assertEquals('2.0000050000000000000000', $math->add('1.000002', '1.000003'));
    }
}