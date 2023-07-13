<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class BankAccountNumber extends Constraint
{
    public string $message = 'The bank account number "{{ value }}" is not valid.';
}
