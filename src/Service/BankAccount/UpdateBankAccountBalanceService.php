<?php

declare(strict_types=1);

namespace App\Service\BankAccount;

use App\Dto\BankAccountUpdateBalanceDto;
use App\Repository\BankAccountRepository;
use Money\Currency;
use Money\Money;
use Symfony\Component\Uid\Uuid;

class UpdateBankAccountBalanceService
{
    public function __construct(private readonly BankAccountRepository $bankAccountRepository)
    {
    }

    public function updateBalance(Uuid $bankAccountUuid, BankAccountUpdateBalanceDto $bankAccountUpdateBalanceDto): Uuid
    {
        $bankAccount = $this->bankAccountRepository->findByUuid($bankAccountUuid);
        $bankAccount->addToBalance(new Money(
            $bankAccountUpdateBalanceDto->amount,
            new Currency($bankAccountUpdateBalanceDto->currency)
        ));
        $this->bankAccountRepository->update($bankAccount, true);

        return $bankAccount->getUuid();
    }
}
