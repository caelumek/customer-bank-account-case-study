<?php

declare(strict_types=1);

namespace App\Resources;

use App\Entity\CustomerEntity;
use Symfony\Component\Uid\Uuid;

final readonly class CustomerBaseResource implements \JsonSerializable
{
    public static function fromEntity(CustomerEntity $customer): self
    {
        return new self(
            $customer->getUuid(),
            $customer->getFirstName(),
            $customer->getLastName(),
        );
    }

    public function __construct(
        public Uuid $uuid,
        public string $firstName,
        public string $lastName,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
