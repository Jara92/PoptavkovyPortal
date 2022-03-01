<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\InquiryValue;
use App\Repository\Interfaces\Inquiry\IInquiryValueRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiryValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiryValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiryValue[]    findAll()
 * @method InquiryValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryValueRepository extends ServiceEntityRepository implements IInquiryValueRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiryValue::class);
    }

    /**
     * @inheritdoc
     */
    public function figureOut(string $value): ?InquiryValue
    {
        $qb = $this->createQueryBuilder("v");
        $qb->andWhere($qb->expr()->like("v.title", ":value"))
            ->setParameter("value", "%" . $value . "%");

        try {
            $result = $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
            return $result;
        } // This should not really happen because there is a maximum of 1 element thanks to ->setMaxResults(1)
        catch (NonUniqueResultException $e) {
            return null;
        }
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
