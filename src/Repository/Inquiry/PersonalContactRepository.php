<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\PersonalContact;
use App\Repository\Interfaces\Inquiry\IPersonalContactRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonalContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalContact[]    findAll()
 * @method PersonalContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalContactRepository extends ServiceEntityRepository implements IPersonalContactRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalContact::class);
    }
}
