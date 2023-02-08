<?php

namespace App\Repository;

use App\Entity\MoneyOutCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoneyOutCategories>
 *
 * @method MoneyOutCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoneyOutCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoneyOutCategories[]    findAll()
 * @method MoneyOutCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoneyOutCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoneyOutCategories::class);
    }

    public function save(MoneyOutCategories $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MoneyOutCategories $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    call function in Money out controller as part of upload process?
    public function addMoneyOutCategories(EntityManagerInterface $entityManager)
    {
        $categoryAndType = MoneyOutCategories::getPaymentCategories();

        foreach ($categoryAndType as $paymentCategory => $paymentType) {
            $moneyOutCategory = new MoneyOutCategories($paymentCategory, $paymentType);
            $entityManager->persist($moneyOutCategory);
        }

        $entityManager->flush();

        return null;
    }

//    /**
//     * @return MoneyOutCategories[] Returns an array of MoneyOutCategories objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MoneyOutCategories
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
