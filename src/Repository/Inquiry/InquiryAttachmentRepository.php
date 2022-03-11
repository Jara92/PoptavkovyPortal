<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\InquiryAttachment;
use App\Repository\Interfaces\Inquiry\IInquiryAttachmentRepository;
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
}
