<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Traits\PaginatedRepositoryTrait;
use App\Tools\Filter\InquiryFilter;
use App\Repository\Interfaces\Inquiry\IInquiryIRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Tools\Pagination\PaginationData;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Inquiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inquiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inquiry[]    findAll()
 * @method Inquiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquiryRepository extends ServiceEntityRepository implements IInquiryIRepository
{
    use PaginatedRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inquiry::class);
    }

    /**
     * @param InquiryFilter $filter
     * @param PaginationData $paginationData
     * @return Inquiry[]
     * @throws Exception
     */
    public function findByFilter(InquiryFilter $filter, PaginationData $paginationData): array
    {
        $queryBuilder = $this->createQueryBuilder("i");

        // Filter by author
        if ($filter->getAuthor()) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq("i.author", ":author"))
                ->setParameter("author", $filter->getAuthor());
        }

        // Filter by text
        if ($filter->getText()) {
            $queryBuilder->andWhere($queryBuilder->expr()->like("i.title", ":text"))
                ->setParameter("text", "%" . $filter->getText() . "%");
        }

        // Filter by types - Inquiry type must be in types array.
        if (!empty($filter->getTypes())) {
            $queryBuilder->andWhere($queryBuilder->expr()->in("i.type", ":types"))
                ->setParameter("types", $filter->getTypes());
        }

        // Filter by regions - Inquiry region must be in types array.
        if (!empty($filter->getRegions())) {
            $queryBuilder->andWhere($queryBuilder->expr()->in("i.region", ":regions"))
                ->setParameter("regions", $filter->getRegions());
        }

        // Filter by regions - One of inquiry category must be in categories array.
        if (!empty($filter->getCategories())) {
            // TODO: Filter by categories
        }

        // Filter by state - Inquiry state must be in states array.
        if (!empty($filter->getStates())) {
            $queryBuilder->andWhere($queryBuilder->expr()->in("i.state", ":states"))
                ->setParameter("states", $filter->getStates());
        }

        // Get final query
        $query = $queryBuilder->getQuery();

        // Paginate result
        $this->paginate($query, $paginationData);

        return $query->getResult();
    }

    // /**
    //  * @return Inquiry[] Returns an array of Inquiry objects
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
    public function findOneBySomeField($value): ?Inquiry
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
