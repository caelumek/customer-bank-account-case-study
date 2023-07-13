<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CustomerUpdateDto
{
    public function __construct(
        #[Assert\NotBlank()]
        public string $firstName,
        #[Assert\NotBlank()]
        public string $lastName,
    ) {
    }
}
