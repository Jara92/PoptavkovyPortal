<?php

namespace App\Business\Service;

use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\InquiryState;
use App\Repository\Interfaces\Inquiry\IInquiryStateRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<CompanyContact, int>
 */
class InquiryStateService extends AService
{
    /** @var IInquiryStateRepository */
    protected IRepository $repository;

    public function __construct(IInquiryStateRepository $inquiryRepository)
    {
        parent::__construct($inquiryRepository);
    }

    public function readByAlias(string $alias): ?InquiryState
    {
        return $this->repository->findOneBy(["alias" => $alias]);
    }
}