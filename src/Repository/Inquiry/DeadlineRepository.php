<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\Deadline;
use App\Repository\Interfaces\Inquiry\IDeadlineRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deadline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deadline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deadline[]    findAll()
 * @method Deadline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeadlineRepository extends ServiceEntityRepository implements IDeadlineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deadline::class);
    }

    /**
     * @inheritdoc
     */
    public function figureOut(string $value): ?Deadline
    {
        $qb = $this->createQueryBuilder("d");
        $qb->andWhere($qb->expr()->like("d.title", ":value"))
            ->setParameter("value", "%" . $value . "%");

        try {
            $result = $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
            return $result;
        } // This should not really happen because there is a maximum of 1 element thanks to ->setMaxResults(1)
        catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
