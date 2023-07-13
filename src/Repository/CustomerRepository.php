<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CustomerEntity;
use App\Model\Customer;
use App\Model\SocialSecurityNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<CustomerEntity>
 *
 * @method CustomerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerEntity[]    findAll()
 * @method CustomerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerEntity::class);
    }

    public function update(Customer $customer, bool $flush = false): void
    {
        $entity = $this->findOneBy(['uuid' => $customer->getUuid()])
            ?? throw new EntityNotFoundException();
        $entity
            ->setFirstName($customer->getFirstName()->toString())
            ->setLastName($customer->getLastName()->toString());
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function create(Customer $customer, bool $flush = false): void
    {
        $entity = new CustomerEntity(
            $customer->getUuid(),
            $customer->getFirstName()->toString(),
            $customer->getLastName()->toString(),
            $customer->getSocialSecurityNumber()->getValue()
        );
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return ArrayCollection<\JsonSerializable>
     */
    public function findAllAsResource(string $resourceClass): ArrayCollection
    {
        $entities = $this->findAll();

        return new ArrayCollection(
            array_map(
                static fn(CustomerEntity $entity) => $resourceClass::fromEntity($entity),
                $entities,
            )
        );
    }

    public function findByUuidAsResource(Uuid $uuid, string $resourceClass): \JsonSerializable
    {
        $entity = $this->findOneBy(['uuid' => $uuid])
            ?? throw new EntityNotFoundException('Customer not found');

        return $resourceClass::fromEntity($entity);
    }

    public function findByUuid(Uuid $uuid): Customer
    {
        $entity = $this->findOneBy(['uuid' => $uuid])
            ?? throw new EntityNotFoundException('Customer not found');

        return new Customer(
            $entity->getUuid(),
            new UnicodeString($entity->getFirstName()),
            new UnicodeString($entity->getLastName()),
            new SocialSecurityNumber($entity->getSocialSecurityNumber()),
        );
    }
}
