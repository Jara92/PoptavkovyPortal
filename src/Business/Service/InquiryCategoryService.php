<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryCategory;
use App\Repository\Interfaces\Inquiry\IInquiryCategoryRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about InquiryCategories.
 * @extends  AService<InquiryCategory, int>
 */
class InquiryCategoryService extends AService
{
    /** @var IInquiryCategoryRepository */
    public IRepository $repository;

    public function __construct(IInquiryCategoryRepository $categoryRepository)
    {
        parent::__construct($categoryRepository);
    }

    public function readByAlias(string $alias): ?Inquiry
    {
        return $this->repository->findOneBy(["alias" => $alias]);
    }

    /**
     * Returns all categories without a parent.
     * @param array|string[] $orderBy
     * @return InquiryCategory[]
     */
    public function readAllRootCategories(array $orderBy = ["title" => "ASC"]): array
    {
        return $this->repository->findRootCategories($orderBy);
    }

    /**
     * Returns all categories with a parent category.
     * @param array|string[] $orderBy
     * @return InquiryCategory[]
     */
    public function readAllSubCategories(array $orderBy = ["parent" => "ASC", "title" => "ASC"]): array
    {
        return $this->repository->findSubCategories($orderBy);
    }
}