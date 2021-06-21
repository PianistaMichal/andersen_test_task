<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static OperationCurrency EUR()
 * @method static OperationCurrency USD()
 * @method static OperationCurrency JPY()
 */
class OperationCurrency extends Enum
{
    private const EUR = 'EUR';
    private const USD = 'USD';
    private const JPY = 'JPY';
}