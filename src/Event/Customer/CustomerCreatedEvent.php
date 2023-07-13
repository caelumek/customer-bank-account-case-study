<?php

declare(strict_types=1);

namespace App\Event\Customer;

use Symfony\Component\Uid\Uuid;

final readonly class CustomerCreatedEvent
{
    public function __construct(
        public Uuid $uuid,
    ) {
    }
}
