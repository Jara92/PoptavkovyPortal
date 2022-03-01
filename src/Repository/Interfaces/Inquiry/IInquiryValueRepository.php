<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\InquiryValue;
use App\Repository\Interfaces\IRepository;

/**
 * @implements IRepository<InquiryValue, int>
 */
interface IInquiryValueRepository extends IRepository
{
    /**
     * Returns InquiryValue corresponding to the given string.
     * @param string $value
     * @return InquiryValue|null
     */
    public function figureOut(string $value): ?InquiryValue;
}