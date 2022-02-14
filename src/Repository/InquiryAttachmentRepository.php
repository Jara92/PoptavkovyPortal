<?php

namespace App\Repository;

use App\Entity\Inquiry\InquiryAttachment;
use App\Repository\Interfaces\IInquiryAttachmentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiryAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiryAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiryAttachment[]    findAll()
 * @method InquiryAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryAttachmentRepository extends ServiceEntityRepository implements IInquiryAttachmentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiryAttachment::class);
    }

    // /**
    //  * @return InquiryAttachment[] Returns an array of InquiryAttachment objects
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
    public function findOneBySomeField($value): ?InquiryAttachment
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
