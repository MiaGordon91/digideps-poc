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

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findSummaryOfCategoryItemsByDeputyId($deputyId): array
    {
        $conn = $this->getEntityManager()
                ->getConnection();

        $sql = 'SELECT category, SUM(amount) AS amount FROM money_out WHERE deputy_user_id = :deputyId GROUP BY category';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':deputyId', $deputyId);

        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }
}
