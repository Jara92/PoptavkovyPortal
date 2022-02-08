<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Filter\InquiryFilter;
use App\Filter\Pagination;
use App\Repository\Interfaces\IInquiryIRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about inquiries and its subclasses.
 * @extends  AService<Inquiry, int>
 */
class InquiryService extends AService
{
    /** @var IInquiryIRepository */
    protected IRepository $repository;

    public function __construct(IInquiryIRepository $inquiryRepository)
    {
        parent::__construct($inquiryRepository);
    }

    public function readByAlias(string $alias):?Inquiry
    {
        return $this->repository->findOneBy(["alias" => $alias]);
    }

    /**
     * Returns filtered results.
     * @param InquiryFilter $filter
     */
    public function readAllFiltered(InquiryFilter $filter, Pagination $paginator)
    {
        return $this->repository->findByFilter($filter, $paginator);
    }
}