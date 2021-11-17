<?php

namespace App\Repository;

use App\Entity\InquiryValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiryValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiryValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiryValue[]    findAll()
 * @method InquiryValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiryValue::class);
    }

    // /**
    //  * @return InquiryValue[] Returns an array of InquiryValue objects
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
    public function findOneBySomeField($value): ?InquiryValue
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
