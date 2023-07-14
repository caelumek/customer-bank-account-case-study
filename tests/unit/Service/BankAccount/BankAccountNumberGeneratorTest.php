<?php

declare(strict_types=1);

namespace App\Tests\unit\Service\BankAccount;

use App\Model\BankAccountNumber;
use App\Repository\BankAccountRepository;
use App\Service\BankAccount\BankAccountNumberGenerator;
use PHPUnit\Framework\TestCase;

final class BankAccountNumberGeneratorTest extends TestCase
{
    public function testBankAccountNumberGeneratorSuccess(): void
    {
        $bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $bankAccountRepository->method('exists')
            ->willReturn(false);

        $bankAccountNumberGenerator = new BankAccountNumberGenerator($bankAccountRepository);

        $bankAccountNumber = $bankAccountNumberGenerator->generate();
        self::assertIsString($bankAccountNumber->getValue());
        self::assertEquals(11, strlen($bankAccountNumber->getValue()));
        self::assertTrue(BankAccountNumber::validate($bankAccountNumber->getValue()));
    }

    public function testBankAccountNumberGeneratorFailsToGenerateUniqueNumber(): void
    {
        $bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $bankAccountRepository->method('exists')
            ->willReturn(true);

        $bankAccountNumberGenerator = new BankAccountNumberGenerator($bankAccountRepository);

        $this->expectExceptionMessage('Could not generate a unique bank account number.');
        $bankAccountNumberGenerator->generate();
    }

    public function testBankAccountNumberGeneratorGeneratesNumberAfterFailAtFirstTry(): void
    {
        $bankAccountRepository = $this->createMock(BankAccountRepository::class);
        $bankAccountRepository
            ->method('exists')
            ->willReturnOnConsecutiveCalls(true, false);

        $bankAccountNumberGenerator = new BankAccountNumberGenerator($bankAccountRepository);

        $bankAccountNumber = $bankAccountNumberGenerator->generate();
        self::assertIsString($bankAccountNumber->getValue());
        self::assertEquals(11, strlen($bankAccountNumber->getValue()));
        self::assertTrue(BankAccountNumber::validate($bankAccountNumber->getValue()));
    }
}
