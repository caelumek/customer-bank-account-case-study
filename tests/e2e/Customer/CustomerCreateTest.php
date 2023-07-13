<?php

declare(strict_types=1);

namespace App\Tests\e2e\Customer;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerCreateTest extends WebTestCase
{
    public function testCreateOk(): void
    {
        $client = self::createClient();
        $client->jsonRequest('POST', 'customers', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'socialSecurityNumber' => '123456789',
        ]);
        /** @var JsonResponse $response */
        $response = $client->getResponse();
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        self::assertArrayHasKey('uuid', $body);
        self::assertSame('John', $body['firstName']);
        self::assertSame('Doe', $body['lastName']);
    }
}
