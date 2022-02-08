<?php

namespace App\Repository\Interfaces;

use App\Entity\Inquiry\Inquiry;
use App\Filter\InquiryFilter;
use App\Filter\Pagination;

/**
 * @implements IRepository<Inquiry, int>
 */
interface IInquiryIRepository extends IRepository
{
    /**
     * Returns results given by filter object.
     * @param InquiryFilter $filter
     * @param Pagination $pagination
     * @return mixed
     */
    public function findByFilter(InquiryFilter $filter, Pagination $pagination);
}