<?php

declare(strict_types=1);

namespace App\Model;

enum BankAccountType: string
{
    case ORGANIZATION = 'organization';
    case PRIVATE = 'private';
}
