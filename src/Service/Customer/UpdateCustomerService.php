<?php

declare(strict_types=1);

namespace App\Service\Customer;

use App\Dto\CustomerUpdateDto;
use App\Repository\CustomerRepository;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

final class UpdateCustomerService
{
    public function __construct(private readonly CustomerRepository $customerRepository)
    {
    }

    public function update(Uuid $customerUuid, CustomerUpdateDto $customerUpdateDto): Uuid
    {
        $customer = $this->customerRepository->findByUuid($customerUuid);
        $customer->updatePersonalData(
            new UnicodeString($customerUpdateDto->firstName),
            new UnicodeString($customerUpdateDto->lastName),
        );
        $this->customerRepository->update($customer, true);

        return $customer->getUuid();
    }
}
