<?php

declare(strict_types=1);

namespace App\Resources;

use App\Entity\CustomerEntity;
use Symfony\Component\Uid\Uuid;

final readonly class CustomerDetailsResource implements \JsonSerializable
{
    public static function fromEntity(CustomerEntity $customer): self
    {
        return new self(
            $customer->getUuid(),
            $customer->getFirstName(),
            $customer->getLastName(),
            $customer->getSocialSecurityNumber(),
        );
    }

    public function __construct(
        public Uuid $uuid,
        public string $firstName,
        public string $lastName,
        public string $socialSecurityNumber,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'socialSecurityNumber' => $this->socialSecurityNumber,
        ];
    }
}
