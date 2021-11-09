<?php

namespace App\Repository;

use App\Entity\PersonalInquiry;
use App\Repository\Interfaces\IPersonalInquiryIRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonalInquiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalInquiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalInquiry[]    findAll()
 * @method PersonalInquiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalInquiryRepository extends ServiceEntityRepository implements IPersonalInquiryIRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalInquiry::class);
    }

    // /**
    //  * @return PersonalInquiry[] Returns an array of PersonalInquiry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonalInquiry
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
