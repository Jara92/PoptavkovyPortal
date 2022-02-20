<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\PersonalContact;
use App\Repository\Interfaces\Inquiry\IPersonalContactRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonalContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalContact[]    findAll()
 * @method PersonalContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalContactRepository extends ServiceEntityRepository implements IPersonalContactRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalContact::class);
    }

    // /**
    //  * @return PersonalContact[] Returns an array of PersonalContact objects
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
    public function findOneBySomeField($value): ?PersonalContact
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
