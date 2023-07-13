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

final class CustomerIndexTest extends WebTestCase
{
    public function testListOk(): void
    {
        $client = self::createClient();
        $this->createCustomer();
        $client->jsonRequest('GET', 'customers');
        self::assertSame(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
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
