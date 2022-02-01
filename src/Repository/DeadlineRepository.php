<?php

namespace App\Repository;

use App\Entity\Inquiry\Deadline;
use App\Repository\Interfaces\IDeadlineRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deadline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deadline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deadline[]    findAll()
 * @method Deadline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeadlineRepository extends ServiceEntityRepository implements IDeadlineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deadline::class);
    }

    // /**
    //  * @return Deadline[] Returns an array of Deadline objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Deadline
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
