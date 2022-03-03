<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\Offer;
use App\Entity\User;
use App\Repository\Interfaces\Inquiry\IOfferRepository;
use App\Repository\Traits\OrderedRepositoryTrait;
use App\Repository\Traits\PaginatedRepositoryTrait;
use App\Tools\Pagination\PaginationData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository implements IOfferRepository
{
    use PaginatedRepositoryTrait;
    use OrderedRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function findByAuthor(User $author, PaginationData $data = null, array $ordering = []): array
    {
        $qb = $this->createQueryBuilder("o");
        $qb->where($qb->expr()->eq("o.author", ":author"))
            ->setParameter("author", $author);

        // Ordering
        $this->orderBy($qb, "o", $ordering);

        // Get final query
        $query = $qb->getQuery();

        // Paginate result
        if ($data) {
            $this->paginate($query, $data);
        }

        return $query->getResult();
    }

    // /**
    //  * @return Offer[] Returns an array of Offer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offer
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
