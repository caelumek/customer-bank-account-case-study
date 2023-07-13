<?php

declare(strict_types=1);

namespace App\Service\BankAccount;

use App\Dto\BankAccountCreateDto;
use App\Model\BankAccount;
use App\Model\BankAccountType;
use App\Repository\BankAccountRepository;
use Money\Currency;
use Money\Money;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class CreateBankAccountService
{
    public function __construct(
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly BankAccountNumberGenerator $bankAccountNumberGenerator,
    ) {
    }

    public function create(BankAccountCreateDto $bankAccountCreateDto): Uuid
    {
        $bankAccount = new BankAccount(
            Uuid::v4(),
            $bankAccountCreateDto->customerUuid,
            $this->bankAccountNumberGenerator->generate(),
            BankAccountType::from($bankAccountCreateDto->type),
            new Currency($bankAccountCreateDto->currency),
            new Money(0, new Currency($bankAccountCreateDto->currency)),
            new UnicodeString($bankAccountCreateDto->name),
        );
        $this->bankAccountRepository->create($bankAccount, true);

        return $bankAccount->getUuid();
    }
}
