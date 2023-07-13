<?php

declare(strict_types=1);

namespace App\Tests\e2e\Customer;

use App\Entity\CustomerEntity;
use App\Model\Customer;
use App\Model\SocialSecurityNumber;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class CustomerShowTest extends WebTestCase
{
    public function testShowOk(): void
    {
        $client = self::createClient();

        $customer = $this->createCustomer();
        $client->jsonRequest('GET', sprintf('customers/%s', $customer->getUuid()));
        /** @var JsonResponse $response */
        $response = $client->getResponse();
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
        self::assertSame($customer->getUuid()->toRfc4122(), $body['uuid']);
        self::assertSame($customer->getFirstName()->toString(), $body['firstName']);
        self::assertSame($customer->getLastName()->toString(), $body['lastName']);
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
