<?php

declare(strict_types=1);

namespace App\Model;

use Webmozart\Assert\Assert;

final class SocialSecurityNumber
{
    public function __construct(private readonly string $socialSecurityNumber)
    {
        Assert::lengthBetween(
            $socialSecurityNumber,
            2,
            30,
            'Social security number must be between 10 and 12 characters long'
        );
    }

    public function getValue(): string
    {
        return $this->socialSecurityNumber;
    }
}
