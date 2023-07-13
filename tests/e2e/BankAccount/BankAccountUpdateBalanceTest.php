<?php

declare(strict_types=1);

namespace App\Tests\e2e\BankAccount;

use App\Entity\BankAccountEntity;
use App\Entity\CustomerEntity;
use App\Model\BankAccount;
use App\Model\BankAccountNumber;
use App\Model\BankAccountType;
use App\Model\Customer;
use App\Model\SocialSecurityNumber;
use App\Repository\BankAccountRepository;
use App\Repository\CustomerRepository;
use Money\Currency;
use Money\Money;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class BankAccountUpdateBalanceTest extends WebTestCase
{
    public function testUpdateIncreaseBalanceOk(): void
    {
        $client = self::createClient();
        $bankAccount = $this->createBankAccount();
        self::assertSame(10000, (int)$bankAccount->getBalance()->getAmount());

        $client->jsonRequest('PATCH', sprintf('bank-accounts/%s/balance', $bankAccount->getUuid()), [
            'amount' => 100,
            'currency' => 'EUR',
        ]);
        /** @var JsonResponse $response */
        $response = $client->getResponse();
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertSame(10100, $body['balance']['amount']);
    }

    public function testUpdateDecreaseBalanceOk(): void
    {
        $client = self::createClient();
        $bankAccount = $this->createBankAccount();
        self::assertSame(10000, (int)$bankAccount->getBalance()->getAmount());

        $client->jsonRequest('PATCH', sprintf('bank-accounts/%s/balance', $bankAccount->getUuid()), [
            'amount' => -100,
            'currency' => 'EUR',
        ]);
        /** @var JsonResponse $response */
        $response = $client->getResponse();
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertSame(9900, $body['balance']['amount']);
    }

    public function testUpdateNegativeBalanceFail(): void
    {
        $client = self::createClient();
        $bankAccount = $this->createBankAccount();
        self::assertSame(10000, (int)$bankAccount->getBalance()->getAmount());

        $client->jsonRequest('PATCH', sprintf('bank-accounts/%s/balance', $bankAccount->getUuid()), [
            'amount' => -100000,
            'currency' => 'EUR',
        ]);
        /** @var JsonResponse $response */
        $response = $client->getResponse();
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        self::assertSame('Balance cannot be negative', $body['detail']);
    }

    private function createBankAccount(): BankAccount
    {
        /** @var BankAccountRepository $bankAccountRepository */
        $bankAccountRepository = self::$kernel->getContainer()->get('doctrine')->getManager()->getRepository(BankAccountEntity::class);

        $customer = $this->createCustomer();
        $bankAccount = new BankAccount(
            Uuid::v4(),
            $customer->getUuid(),
            BankAccountNumber::generate(),
            BankAccountType::PRIVATE,
            new Currency('EUR'),
            new Money(10000, new Currency('EUR')),
            new UnicodeString('Private Account #1'),
        );
        $bankAccountRepository->create($bankAccount, true);

        return $bankAccount;
    }

    private function createCustomer(): Customer
    {
        /** @var CustomerRepository $customerRepository */
        $customerRepository = self::$kernel->getContainer()->get('doctrine')->getManager()->getRepository(CustomerEntity::class);
        $customer = new Customer(
            Uuid::v4(),
            new UnicodeString('John'),
            new UnicodeString('Doe'),
            new SocialSecurityNumber('123456789'),
        );
        $customerRepository->create($customer, true);

        return $customer;
    }
}
