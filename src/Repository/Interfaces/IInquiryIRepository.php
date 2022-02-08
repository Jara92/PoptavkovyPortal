<?php

namespace App\Repository\Interfaces;

use App\Entity\Inquiry\Inquiry;
use App\Tools\Filter\InquiryFilter;
use App\Tools\Pagination\PaginationData;

/**
 * @implements IRepository<Inquiry, int>
 */
interface IInquiryIRepository extends IRepository
{
    /**
     * Returns results given by filter object.
     * @param InquiryFilter $filter
     * @param PaginationData $paginationData
     * @return mixed
     */
    public function findByFilter(InquiryFilter $filter, PaginationData $paginationData);
}