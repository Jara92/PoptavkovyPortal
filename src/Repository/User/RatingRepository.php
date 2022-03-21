<?php

namespace App\Repository\User;

use App\Entity\User;
use App\Entity\User\Rating;
use App\Repository\Interfaces\User\IRatingRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository implements IRatingRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    /**
     * @inheritDoc
     */
    public function getAverageRatingForTarget(User $user): float
    {
        $qb = $this->createQueryBuilder("r");

        $qb->select("avg(r.rating)")
            ->where("r.target = :target")
            // TODO: Filter only public ratings?
            ->setParameter("target", $user);

        try {
            $rating = $qb->getQuery()->getSingleScalarResult();

            return ($rating) ? $rating : 3;
        } catch (NoResultException | NonUniqueResultException $e) {
            return 3;
        }
    }

    /**
     * @inheritDoc
     */
    public function getRatingValuesCount(User $user): array
    {
        $qb = $this->createQueryBuilder("r", "r.rating");

        $qb->select("r.rating, count(r.id) as cnt")
            ->where("r.target = :target")
            // TODO: Filter only public ratings?
            ->setParameter("target", $user)
            ->orderBy("cnt", "desc")
            ->groupBy("r.rating");

        $result = $qb->getQuery()->getArrayResult();

        // Return array in format [rating_value => count]
        return array_map(fn($item) => $item["cnt"], $result);
    }
}
