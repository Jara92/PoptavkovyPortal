<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\InquirySignedRequest;
use App\Repository\Interfaces\Inquiry\IInquirySignedRequestRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquirySignedRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquirySignedRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquirySignedRequest[]    findAll()
 * @method InquirySignedRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquirySignedRequestRepository extends ServiceEntityRepository implements IInquirySignedRequestRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquirySignedRequest::class);
    }
}
