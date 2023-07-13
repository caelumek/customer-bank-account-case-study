<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\Table(name: 'customers')]
class CustomerEntity
{
    use HasId;
    use HasUuid;

    #[ORM\Column]
    private string $firstName;

    #[ORM\Column]
    private string $lastName;

    #[ORM\Column(unique: true)]
    private string $socialSecurityNumber;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: BankAccountEntity::class)]
    private Collection $bankAccounts;

    public function __construct(
        Uuid $uuid,
        string $firstName,
        string $lastName,
        string $emailAddress,
    ) {
        $this->uuid = $uuid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->socialSecurityNumber = $emailAddress;
        $this->bankAccounts = new ArrayCollection();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getSocialSecurityNumber(): string
    {
        return $this->socialSecurityNumber;
    }

    public function setSocialSecurityNumber(string $socialSecurityNumber): self
    {
        $this->socialSecurityNumber = $socialSecurityNumber;
        return $this;
    }

    public function getBankAccounts(): Collection
    {
        return $this->bankAccounts;
    }

    public function addBankAccount(BankAccountEntity $bankAccount): self
    {
        $this->bankAccounts->contains($bankAccount) || $this->bankAccounts->add($bankAccount);

        return $this;
    }

    public function removeBankAccount(BankAccountEntity $bankAccount): self
    {
        $this->bankAccounts->contains($bankAccount) && $this->bankAccounts->removeElement($bankAccount);

        return $this;
    }
}
