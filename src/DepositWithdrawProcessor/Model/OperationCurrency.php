<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use MyCLabs\Enum\Enum;

class OperationCurrency extends Enum
{
    private const EUR = 'EUR';
    private const USD = 'USD';
    private const JPY = 'JPY';
}