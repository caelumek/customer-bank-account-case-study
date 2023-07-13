<?php

declare(strict_types=1);

namespace App\Service\Customer;

use App\Dto\CustomerCreateDto;
use App\Model\Customer;
use App\Model\SocialSecurityNumber;
use App\Repository\CustomerRepository;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class CreateCustomerService
{
    public function __construct(private readonly CustomerRepository $customerRepository)
    {
    }

    public function create(CustomerCreateDto $customerCreateDto): Uuid
    {
        $customer = new Customer(
            Uuid::v4(),
            new UnicodeString($customerCreateDto->firstName),
            new UnicodeString($customerCreateDto->lastName),
            new SocialSecurityNumber($customerCreateDto->socialSecurityNumber),
        );
        $this->customerRepository->create($customer, true);

        return $customer->getUuid();
    }
}
