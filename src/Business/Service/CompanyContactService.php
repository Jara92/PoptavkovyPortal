<?php

namespace App\Business\Service;

use App\Entity\Inquiry\CompanyContact;
use App\Repository\Interfaces\ICompanyContactRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<CompanyContact, int>
 */
class CompanyContactService extends AService
{
    /** @var ICompanyContactRepository */
    protected $repository;

    public function __construct(ICompanyContactRepository $inquiryRepository, ManagerRegistry $doctrine)
    {
        parent::__construct($inquiryRepository, $doctrine);
    }
}