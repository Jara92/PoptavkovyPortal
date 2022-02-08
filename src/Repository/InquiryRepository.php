<?php

namespace App\Repository;

use App\Entity\Inquiry\Inquiry;
use App\Tools\Filter\InquiryFilter;
use App\Repository\Interfaces\IInquiryIRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Tools\Pagination\PaginationData;
use Doctrine\Persistence\ManagerRegistry;

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
     */
    public function findByFilter(InquiryFilter $filter, PaginationData $paginationData)
    {
        $queryBuilder = $this->createQueryBuilder("i");

        $query = $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->like("i.title", ":text")
            )
            ->setParameter("text", "%" . $filter->getText() . "%")
            ->getQuery();

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
