<?php

namespace App\Repository;

use App\Entity\CompanyInquiry;
use App\Repository\Interfaces\ICompanyInquiryIRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyInquiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyInquiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyInquiry[]    findAll()
 * @method CompanyInquiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyInquiryRepository extends ServiceEntityRepository implements ICompanyInquiryIRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyInquiry::class);
    }

    // /**
    //  * @return CompanyInquiry[] Returns an array of CompanyInquiry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompanyInquiry
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
