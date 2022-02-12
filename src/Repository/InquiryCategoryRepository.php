<?php

namespace App\Repository;

use App\Entity\Inquiry\InquiryCategory;
use App\Repository\Interfaces\IInquiryCategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiryCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiryCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiryCategory[]    findAll()
 * @method InquiryCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryCategoryRepository extends ServiceEntityRepository implements IInquiryCategoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiryCategory::class);
    }

    /**
     * @inheritdoc
     */
    public function findRootCategories(array $orderBy = null): array
    {
        $criteria = ["parent" => null];

        return $this->findBy($criteria, $orderBy);
    }

    // /**
    //  * @return InquiryCategory[] Returns an array of InquiryCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InquiryCategory
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
