<?php

declare(strict_types=1);

namespace App\Model;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class BankAccountNumber
{
    public function __construct(private readonly string $bankAccountNumber)
    {
        self::validate($this->bankAccountNumber);
    }

    public function getValue(): string
    {
        return $this->bankAccountNumber;
    }

    public static function generate(): self
    {
        $numberWithoutChecksum = (string)random_int(1000000000, 9999999999);
        $checksumNumber = (string)self::calcChecksum($numberWithoutChecksum);

        return new self($numberWithoutChecksum . $checksumNumber);
    }

    public static function validate(string $accountNumber): bool
    {
        $calculatedChecksum = self::calcChecksum($accountNumber);
        $accountNumberChecksum = (int)substr($accountNumber, -1);

        $calculatedChecksum === $accountNumberChecksum ?: throw new InvalidArgumentException('Invalid account number');

        return true;
    }

    private static function calcChecksum(string $accountNumber): int
    {
        $digits = str_split($accountNumber);

        $weights = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $checksum = array_reduce(
            $weights,
            function ($carry, $weight) use ($digits) {
                $carry += $weight * array_shift($digits);

                return $carry;
            },
            0
        ) % 11;

        if ($checksum % 10 !== 0) {
            $checksum = 11 - $checksum;
        }

        if ($checksum === 10) {
            $checksum = 0;
        }

        return $checksum;
    }
}
