<?php

namespace App\Business\Service;

use App\Repository\Interfaces\IInquiryTypeRepository;
use Doctrine\Persistence\ManagerRegistry;

class InquiryTypeService extends AService
{
    /** @var IInquiryTypeRepository */
    protected $repository;

    public function __construct(IInquiryTypeRepository $repository, ManagerRegistry $doctrine)
    {
        parent::__construct($repository, $doctrine);
    }

    public function getInquiryTypeByAlias(string $alias): ?\App\Entity\Inquiry\InquiryType
    {
        return $this->repository->findOneByAlias($alias);
    }
}