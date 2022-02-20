<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\InquiryType;
use App\Repository\Interfaces\IRepository;

/**
 * @implements IRepository<InquiryType, int>
 */
interface IInquiryTypeRepository extends IRepository
{
    /**
     * Returns inquiry type by its alias.
     * @param string $alias Alias.
     * @return InquiryType | null
     */
    public function findOneByAlias(string $alias):?InquiryType;
}