<?php

namespace App\Repository;

use App\Entity\Inquiry\InquiryState;
use App\Repository\Interfaces\IInquiryStateRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiryState|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiryState|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiryState[]    findAll()
 * @method InquiryState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryStateRepository extends ServiceEntityRepository implements IInquiryStateRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiryState::class);
    }

    // /**
    //  * @return InquiryState[] Returns an array of InquiryState objects
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
    public function findOneBySomeField($value): ?InquiryState
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
