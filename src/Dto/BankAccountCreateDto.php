<?php
declare(strict_types=1);

namespace App\Dto;

use App\Model\BankAccountType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

readonly class BankAccountCreateDto
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Uuid()]
        public Uuid $customerUuid,
        #[Assert\NotBlank()]
        #[Assert\Choice(choices: [
            BankAccountType::ORGANIZATION->value,
            BankAccountType::PRIVATE->value,
        ])]
        public string $type,
        #[Assert\NotBlank()]
        #[Assert\Choice(choices: [
            'USD',
            'EUR',
            'GBP',
        ])]
        public string $currency,
        #[Assert\NotBlank()]
        public string $name,
    ) {
    }

}
