<?php

declare(strict_types=1);

namespace App\Tests\e2e\BankAccount;

use App\Entity\CustomerEntity;
use App\Model\Customer;
use App\Model\SocialSecurityNumber;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class BankAccountCreateTest extends WebTestCase
{
    public function testCreateOk(): void
    {
        $client = self::createClient();

        $customer = $this->createCustomer();

        $client->jsonRequest('POST', 'bank-accounts', [
            'customerUuid' => $customer->getUuid()->toRfc4122(),
            'type' => 'private',
            'currency' => 'EUR',
            'name' => 'Private Account #1'
        ]);
        /** @var JsonResponse $response */
        $response = $client->getResponse();
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        self::assertArrayHasKey('uuid', $body);
        self::assertSame($customer->getUuid()->toRfc4122(), $body['customerUuid']);
        self::assertSame('private', $body['type']);
        self::assertSame('Private Account #1', $body['name']);
        self::assertEquals(11, strlen($body['accountNumber']));
        self::assertSame(0, $body['balance']['amount']);
        self::assertSame('EUR', $body['balance']['currency']);
        self::assertSame('EUR', $body['currency']);
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
