<?php

namespace App\Repository;

use App\Entity\Inquiry\InquiryType;
use App\Repository\Interfaces\IInquiryTypeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiryType[]    findAll()
 * @method InquiryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryTypeRepository extends ServiceEntityRepository implements IInquiryTypeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiryType::class);
    }

    // /**
    //  * @return InquiryType[] Returns an array of InquiryType objects
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
    public function findOneBySomeField($value): ?InquiryType
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @inheritDoc
     * @throws NonUniqueResultException
     * @return InquiryType | null
     */
    public function findOneByAlias(string $alias): ?InquiryType
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.alias = :alias')
            ->setParameter('alias', $alias)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
