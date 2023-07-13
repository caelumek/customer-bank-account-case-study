<?php

declare(strict_types=1);

namespace App\Service\BankAccount;

use App\Dto\BankAccountUpdateDto;
use App\Repository\BankAccountRepository;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

class UpdateBankAccountService
{
    public function __construct(private readonly BankAccountRepository $bankAccountRepository)
    {
    }

    public function update(Uuid $bankAccountUuid, BankAccountUpdateDto $bankAccountUpdateDto): Uuid
    {
        $bankAccount = $this->bankAccountRepository->findByUuid($bankAccountUuid);
        $bankAccount->setName(new UnicodeString($bankAccountUpdateDto->name));
        $this->bankAccountRepository->update($bankAccount, true);

        return $bankAccount->getUuid();
    }
}
