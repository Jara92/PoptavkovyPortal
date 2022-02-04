<?php

namespace App\Business\Service;

use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\InquiryState;
use App\Repository\Interfaces\IInquiryStateRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<CompanyContact, int>
 */
class InquiryStateService extends AService
{
    /** @var IInquiryStateRepository */
    protected $repository;

    public function __construct(IInquiryStateRepository $inquiryRepository, ManagerRegistry $doctrine)
    {
        parent::__construct($inquiryRepository, $doctrine);
    }

    public function readByAlias(string $alias): ?InquiryState
    {
        return $this->repository->findOneBy(["alias" => $alias]);
    }
}