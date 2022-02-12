<?php

namespace App\Repository\Interfaces;

use App\Entity\Inquiry\InquiryCategory;

/**
 * @implements IRepository<InquiryCategory, int>
 */
interface IInquiryCategoryRepository extends IRepository
{
    /**
     * Returns all categories without a parent.
     * @param array|null $orderBy
     * @return InquiryCategory[]
     */
    public function findRootCategories(array $orderBy = null): array;
}