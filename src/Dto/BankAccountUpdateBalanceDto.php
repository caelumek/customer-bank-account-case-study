<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BankAccountUpdateBalanceDto
{
    public function __construct(
        #[Assert\NotBlank()]
        public int $amount,
        #[Assert\NotBlank()]
        #[Assert\Choice(choices: [
            'USD',
            'EUR',
            'GBP',
        ])]
        public string $currency,
    ) {
    }
}
