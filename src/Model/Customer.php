<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class Customer
{
    private ArrayCollection $bankAccounts;

    public function __construct(
        private readonly Uuid $uuid,
        private UnicodeString $firstName,
        private UnicodeString $lastName,
        private readonly SocialSecurityNumber $socialSecurityNumber,
        ArrayCollection $bankAccounts = null,
        private BankAccount|null $preferredBankAccount = null,
    )
    {
        $this->bankAccounts = $bankAccounts ?? new ArrayCollection();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getFirstName(): UnicodeString
    {
        return $this->firstName;
    }

    public function getLastName(): UnicodeString
    {
        return $this->lastName;
    }

    public function getSocialSecurityNumber(): SocialSecurityNumber
    {
        return $this->socialSecurityNumber;
    }

    public function getBankAccounts(): ArrayCollection
    {
        return $this->bankAccounts;
    }

    public function getPreferredBankAccount(): BankAccount|null
    {
        return $this->preferredBankAccount;
    }

    public function updatePersonalData(
        UnicodeString $firstName,
        UnicodeString $lastName,
    ): self {
        $this->firstName = $firstName;
        $this->lastName = $lastName;

        return $this;
    }

    public function setPreferredBankAccount(BankAccount $bankAccount): self
    {
        $this->preferredBankAccount = $bankAccount;
        return $this;
    }
}
