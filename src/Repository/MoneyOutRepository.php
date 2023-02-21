<?php

namespace App\Repository;

use App\Entity\MoneyOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoneyOut>
 *
 * @method MoneyOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoneyOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoneyOut[]    findAll()
 * @method MoneyOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoneyOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoneyOut::class);
    }

    public function save(MoneyOut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MoneyOut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPaymentItemsByDeputyId($deputyId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->Where('p.deputyUser = :deputyId')
            ->setParameter('deputyId', $deputyId)
            ->orderBy('p.amount', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
