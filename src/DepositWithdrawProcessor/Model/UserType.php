<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static UserType PRIVATE ()
 * @method static UserType BUSINESS()
 */
class UserType extends Enum
{
    private const PRIVATE = 'PRIVATE';
    private const BUSINESS = 'BUSINESS';
}