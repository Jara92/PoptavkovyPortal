<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\InquiryCategory;
use App\Repository\Interfaces\IRepository;

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

    /**
     * Returns all categories with a parent category.
     * @param array $orderBy
     * @return InquiryCategory[]
     */
    public function findSubCategories(array $orderBy = []): array;
}