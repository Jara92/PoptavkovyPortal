<?php

namespace App\Repository\Inquiry;

use App\Entity\Inquiry\CompanyContact;
use App\Repository\Interfaces\Inquiry\ICompanyContactRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyContact[]    findAll()
 * @method CompanyContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyContactRepository extends ServiceEntityRepository implements ICompanyContactRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyContact::class);
    }
}
