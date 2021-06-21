<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use MyCLabs\Enum\Enum;

class DepositType extends Enum
{
    private const WITHDRAW = 'WITHDRAW';
    private const DEPOSIT = 'DEPOSIT';
}