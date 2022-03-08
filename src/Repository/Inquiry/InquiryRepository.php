<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\Inquiry;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Repository\Traits\OrderedRepositoryTrait;
use App\Repository\Traits\PaginatedRepositoryTrait;
use App\Tools\Filter\InquiryFilter;
use App\Repository\Interfaces\Inquiry\IInquiryIRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Tools\Pagination\PaginationData;
use Doctrine\ORM\QueryBuilder;
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
    use OrderedRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inquiry::class);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByFilterPaginated(InquiryFilter $filter, PaginationData $paginationData, array $ordering = []): array
    {
        // Get final query
        $qb = $this->getFilterQueryBuilder($filter);
        $this->orderBy($qb, "i", $ordering);

        $query = $qb->getQuery();

        // Paginate result
        $this->paginate($query, $paginationData);

        return $query->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findSimilar(Inquiry $inquiry, int $maxResults = 10, array $ordering = [])
    {
        // TODO: this is just a simple way to find similar articles.
        // Build tmp filter object
        $filter = new InquiryFilter();
        $filter->setCategories($inquiry->getCategories()->toArray());
        $filter->setTypes([$inquiry->getType()]);

        if ($inquiry->getRegion()) {
            $filter->setRegions([$inquiry->getRegion()]);
        }

        $qb = $this->getFilterQueryBuilder($filter);
        $qb->setMaxResults($maxResults);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param InquiryFilter $filter
     * @return QueryBuilder
     */
    private function getFilterQueryBuilder(InquiryFilter $filter): QueryBuilder
    {
        $qb = $this->createQueryBuilder("i");

        // Filter by author
        if ($filter->getAuthor()) {
            $qb->andWhere($qb->expr()->eq("i.author", ":author"))
                ->setParameter("author", $filter->getAuthor());
        }

        // Filter by text
        if ($filter->getText()) {
            $qb->andWhere($qb->expr()->like("i.title", ":text"))
                ->setParameter("text", "%" . $filter->getText() . "%");
        }

        // We need to get string array not InquiryType object array.
        $types = array_map(fn(InquiryType $type) => $type->value, $filter->getTypes());

        // Filter by types - Inquiry type must be in types array.
        if (!empty($filter->getTypes())) {
            $qb->andWhere($qb->expr()->in("i.type", ":types"))
                ->setParameter("types", $types);
        }

        // Filter by regions - Inquiry region must be in types array.
        if (!empty($filter->getRegions())) {
            $qb->andWhere($qb->expr()->in("i.region", ":regions"))
                ->setParameter("regions", $filter->getRegions());
        }

        // Filter by regions - One of inquiry category must be in categories array.
        if (!empty($filter->getCategories())) {
            // TODO: this way may be a performance problem later.
            $qb->innerJoin("i.categories", "cat");

            $qb->andWhere($qb->expr()->orX(
            // Category directly
                $qb->expr()->in("cat", ":categories"),
                // Inquiries usually contain categories with a parent category. We need to filter by the parent too.
                $qb->expr()->in("cat.parent", ":categories"),
            ))->setParameter("categories", $filter->getCategories());
        }

        // We need to get string array not InquiryState object array.
        $states = array_map(fn(InquiryState $type) => $type->value, $filter->getStates());

        // Filter by state - Inquiry state must be in states array.
        if (!empty($filter->getStates())) {
            $qb->andWhere($qb->expr()->in("i.state", ":states"))
                ->setParameter("states", $states);
        }

        return $qb;
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
