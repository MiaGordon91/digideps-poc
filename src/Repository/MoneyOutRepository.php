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
        $current_year = (new \DateTime('now'))->format('Y');

        $conn = $this->getEntityManager()
            ->getConnection();

        $sql = 'SELECT *
                FROM money_out
                WHERE deputy_user_id = :deputyId
                AND EXTRACT(year FROM report_year) = :current_year
                ORDER BY amount';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':deputyId', $deputyId);
        $stmt->bindValue(':current_year', $current_year);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findCategoryItemSummaryByDeputyIdForCurrentYear($deputyId): array
    {
        $conn = $this->getEntityManager()
                ->getConnection();

        $sql = 'SELECT category,
                SUM(amount) AS amount
                FROM money_out
                WHERE EXTRACT(year FROM report_year) = EXTRACT(year from current_date)
                AND deputy_user_id = :deputyId
                GROUP BY category';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':deputyId', $deputyId);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findCategoryItemSummaryByDeputyIdForCurrentAndPreviousYear($deputyId): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $oneYearAgo = (new \DateTime('now'))->modify('-1 Year')->format('Y');

        $sql = 'SELECT EXTRACT(year FROM report_year) as year , category,
                SUM(amount) AS amount
                FROM money_out
                WHERE EXTRACT(year FROM report_year) >= :oneYearAgo
                AND deputy_user_id = :deputyId
                GROUP BY category, EXTRACT(year FROM report_year)';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':deputyId', $deputyId);
        $stmt->bindValue(':oneYearAgo', $oneYearAgo);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }
}
