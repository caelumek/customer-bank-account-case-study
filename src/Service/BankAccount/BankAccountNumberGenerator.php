<?php

declare(strict_types=1);

namespace App\Service\BankAccount;

use App\Model\BankAccountNumber;
use App\Repository\BankAccountRepository;

final class BankAccountNumberGenerator
{
    private const MAX_ITERATIONS = 1000;

    public function __construct(private readonly BankAccountRepository $bankAccountRepository)
    {
    }

    public function generate(): BankAccountNumber
    {
        for ($i = 0; $i < self::MAX_ITERATIONS; $i++) {
            $bankAccountNumber = BankAccountNumber::generate();
            if (!$this->bankAccountRepository->exists($bankAccountNumber)) {
                return $bankAccountNumber;
            }
        }

        throw new \RuntimeException('Could not generate a unique bank account number.');
    }
}
