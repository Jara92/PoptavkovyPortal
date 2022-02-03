<?php

namespace App\Business\Service;

use App\Repository\Interfaces\IInquiryTypeRepository;
use App\Repository\Interfaces\IRepository;

class InquiryTypeService extends AService
{
    /** @var IInquiryTypeRepository */
    protected $repository;

    public function __construct(IInquiryTypeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getInquiryTypeByAlias(string $alias): ?\App\Entity\Inquiry\InquiryType
    {
        return $this->repository->findOneByAlias($alias);
    }
}