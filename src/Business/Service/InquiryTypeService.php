<?php

namespace App\Business\Service;

use App\Entity\Inquiry\InquiryType;
use App\Repository\Interfaces\IInquiryTypeRepository;
use App\Repository\Interfaces\IRepository;
use Doctrine\Persistence\ManagerRegistry;

class InquiryTypeService extends AService
{
    /** @var IInquiryTypeRepository */
    protected IRepository $repository;

    public function __construct(IInquiryTypeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getInquiryTypeByAlias(string $alias): ?InquiryType
    {
        return $this->repository->findOneByAlias($alias);
    }
}