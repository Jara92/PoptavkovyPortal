<?php

namespace App\Repository\Inquiry\Rating;

use App\Entity\Inquiry\Rating\SupplierRating;
use App\Repository\Interfaces\Inquiry\Rating\ISupplierRatingRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupplierRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplierRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplierRating[]    findAll()
 * @method SupplierRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplierRatingRepository extends ServiceEntityRepository implements ISupplierRatingRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplierRating::class);
    }
}
