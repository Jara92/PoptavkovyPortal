<?php

namespace App\Repository\Inquiry\Rating;

use App\Entity\Inquiry\Rating\InquiringRating;
use App\Repository\Interfaces\Inquiry\Rating\IInquiringRatingRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InquiringRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method InquiringRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method InquiringRating[]    findAll()
 * @method InquiringRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiringRatingRepository extends ServiceEntityRepository implements IInquiringRatingRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InquiringRating::class);
    }
}
