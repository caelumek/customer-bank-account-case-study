<?php

declare(strict_types=1);

namespace App\Resources;

use App\Entity\BankAccountEntity;
use Symfony\Component\Uid\Uuid;

final readonly class BankAccountBaseResource implements \JsonSerializable
{
    public static function fromEntity(BankAccountEntity $bankAccount): self
    {
        return new self(
            $bankAccount->getUuid(),
            $bankAccount->getType(),
            $bankAccount->getName(),
            $bankAccount->getAccountNumber(),
            (int)$bankAccount->getBalance()->getAmount(),
            $bankAccount->getBalance()->getCurrency()->getCode(),
            $bankAccount->getCurrency(),
        );
    }

    public function __construct(
        public Uuid $uuid,
        public string $type,
        public string $name,
        public string $accountNumber,
        public int $balanceAmount,
        public string $balanceCurrencyCode,
        public string $bankAccountCurrencyCode,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->type,
            'name' => $this->name,
            'accountNumber' => $this->accountNumber,
            'balance' => [
                'amount' => $this->balanceAmount,
                'currency' => $this->balanceCurrencyCode,
            ],
            'currency' => $this->bankAccountCurrencyCode,
        ];
    }
}
