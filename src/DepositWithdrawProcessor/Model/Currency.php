<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static Currency EUR()
 * @method static Currency USD()
 * @method static Currency JPY()
 */
class Currency extends Enum
{
    private const EUR = 'EUR';
    private const USD = 'USD';
    private const JPY = 'JPY';
}