<?php

declare(strict_types=1);

namespace App\Resources;

use App\Entity\BankAccountEntity;
use Symfony\Component\Uid\Uuid;

final readonly class BankAccountUpdatedResource implements \JsonSerializable
{
    public static function fromEntity(BankAccountEntity $bankAccount): self
    {
        return new self(
            $bankAccount->getUuid(),
            $bankAccount->getName(),
        );
    }

    public function __construct(
        public Uuid $uuid,
        public string $name,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}
