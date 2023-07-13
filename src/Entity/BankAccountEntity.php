<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
#[ORM\Table(name: 'bank_accounts')]
class BankAccountEntity
{
    use HasId;
    use HasUuid;

    #[ORM\Column(unique: true)]
    private string $accountNumber;

    #[ORM\Column(type: 'money')]
    private Money $balance;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column]
    private string $type;

    #[ORM\Column]
    private string $name;

    #[ORM\ManyToOne(targetEntity: CustomerEntity::class, inversedBy: 'bankAccounts')]
    private CustomerEntity $customer;

    public function __construct(
        Uuid $uuid,
        string $accountNumber,
        Money $balance,
        string $currency,
        string $type,
        string $name,
        CustomerEntity $customer
    ) {
        $this->uuid = $uuid;
        $this->accountNumber = $accountNumber;
        $this->balance = $balance;
        $this->currency = $currency;
        $this->type = $type;
        $this->name = $name;
        $this->customer = $customer;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function setBalance(Money $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCustomer(): CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(CustomerEntity $customer): self
    {
        $this->customer = $customer;
        $customer->addBankAccount($this);

        return $this;
    }
}
