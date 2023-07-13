<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BankAccountUpdateDto
{
    public function __construct(
        #[Assert\NotBlank()]
        public string $name,
    ) {
    }
}
