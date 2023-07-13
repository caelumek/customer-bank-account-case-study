<?php

declare(strict_types=1);

namespace App\Tests\e2e\BankAccount;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BankAccountIndexTest extends WebTestCase
{
    public function testListOk(): void
    {
        $client = self::createClient();
        $client->jsonRequest('GET', 'bank-accounts');
        self::assertSame(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
