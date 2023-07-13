<?php

declare(strict_types=1);

namespace App\Model;

use Money\Currency;
use Money\Money;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class BankAccount
{
    public function __construct(
        private readonly Uuid $uuid,
        private readonly Uuid $customerUuid,
        private readonly BankAccountNumber $accountNumber,
        private readonly BankAccountType $type,
        private readonly Currency $currency,
        private Money $balance,
        private UnicodeString $name,
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getCustomerUuid(): Uuid
    {
        return $this->customerUuid;
    }

    public function getAccountNumber(): BankAccountNumber
    {
        return $this->accountNumber;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function addToBalance(Money $money): self
    {
        $this->balance = $this->balance->add($money);

        if ($this->balance->isNegative()) {
            throw new \InvalidArgumentException('Balance cannot be negative');
        }

        return $this;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getType(): BankAccountType
    {
        return $this->type;
    }

    public function getName(): UnicodeString
    {
        return $this->name;
    }

    public function setName(UnicodeString $name): self
    {
        $this->name = $name;

        return $this;
    }
}
