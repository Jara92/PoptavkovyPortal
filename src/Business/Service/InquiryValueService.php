<?php

namespace App\Business\Service;

use App\Entity\Inquiry\InquiryValue;
use App\Repository\Interfaces\Inquiry\IInquiryValueRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about InquiryValues.
 * @extends  AService<InquiryValue, int>
 */
class InquiryValueService extends AService
{
    /** @var IInquiryValueRepository */
    protected IRepository $repository;

    public function __construct(IInquiryValueRepository $inquiryValueRepository)
    {
        parent::__construct($inquiryValueRepository);
    }
}