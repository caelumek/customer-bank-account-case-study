<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BankAccountEntity;
use App\Entity\CustomerEntity;
use App\Model\BankAccount;
use App\Model\BankAccountNumber;
use App\Model\BankAccountType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Money\Currency;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<BankAccountEntity>
 *
 * @method BankAccountEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankAccountEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankAccountEntity[]    findAll()
 * @method BankAccountEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccountEntity::class);
    }

    public function update(BankAccount $bankAccount, bool $flush = false): void
    {
        $entity = $this->findOneBy(['uuid' => $bankAccount->getUuid()])
            ?? throw new EntityNotFoundException();
        $entity
            ->setName($bankAccount->getName()->toString())
            ->setBalance($bankAccount->getBalance());
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function create(BankAccount $bankAccount, bool $flush = false): void
    {
        $customerRepository = $this->_em->getRepository(CustomerEntity::class);
        $entity = new BankAccountEntity(
            $bankAccount->getUuid(),
            $bankAccount->getAccountNumber()->getValue(),
            $bankAccount->getBalance(),
            $bankAccount->getCurrency()->getCode(),
            $bankAccount->getType()->value,
            $bankAccount->getName()->toString(),
            $customerRepository->findOneBy(['uuid' => $bankAccount->getCustomerUuid()]),
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
                static fn(BankAccountEntity $entity) => $resourceClass::fromEntity($entity),
                $entities,
            )
        );
    }

    public function findByUuidAsResource(Uuid $uuid, string $resourceClass): \JsonSerializable
    {
        $entity = $this->findOneBy(['uuid' => $uuid])
            ?? throw new EntityNotFoundException('Bank account not found');

        return $resourceClass::fromEntity($entity);
    }

    public function findByUuid(Uuid $uuid): BankAccount
    {
        $entity = $this->findOneBy(['uuid' => $uuid])
            ?? throw new EntityNotFoundException('Bank account not found');

        return new BankAccount(
            $entity->getUuid(),
            $entity->getCustomer()->getUuid(),
            new BankAccountNumber($entity->getAccountNumber()),
            BankAccountType::from($entity->getType()),
            new Currency($entity->getCurrency()),
            $entity->getBalance(),
            new UnicodeString($entity->getName()),
        );
    }

    public function exists(BankAccountNumber $bankAccountNumber): bool
    {
        return $this->findOneBy(['accountNumber' => $bankAccountNumber->getValue()]) !== null;
    }
}
