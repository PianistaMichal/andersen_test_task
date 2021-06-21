<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static DepositType WITHDRAW()
 * @method static DepositType DEPOSIT()
 */
class DepositType extends Enum
{
    private const WITHDRAW = 'WITHDRAW';
    private const DEPOSIT = 'DEPOSIT';
}