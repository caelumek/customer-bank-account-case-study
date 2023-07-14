<?php

declare(strict_types=1);

namespace App\Tests\unit\Model\BankAccountNumber;

use App\Model\BankAccountNumber;
use PHPUnit\Framework\TestCase;

final class BankAccountNumberValidateTest extends TestCase
{
    /**
     * @dataProvider validAccountNumberProvider
     */
    public function testValidAccountNumberAreOk(string $accountNumber): void
    {
        self::assertTrue(BankAccountNumber::validate($accountNumber));
    }

    private function validAccountNumberProvider(): \Generator
    {
        yield ['65720754367'];
        yield ['85498758292'];
        yield ['47538217780'];
        yield ['90324125235'];
        yield ['44122030540'];
        yield ['92432602635'];
        yield ['00000000000'];
    }

    /**
     * @dataProvider invalidAccountNumberProvider
     */
    public function testInvalidAccountNumberAreDetected(string $accountNumber): void
    {
        $this->expectExceptionMessage('Invalid account number');
        self::assertTrue(BankAccountNumber::validate($accountNumber));
    }

    private function invalidAccountNumberProvider(): \Generator
    {
        yield ['12345678901'];
        yield ['23456789012'];
        yield ['34567890123'];
        yield ['45678901234'];
        yield ['00000000001'];
        yield ['11111111111'];
    }

}
