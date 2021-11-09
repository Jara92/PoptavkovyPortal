<?php

namespace App\Services;

use App\Entity\Inquiry;
use App\Repository\Interfaces\ICompanyInquiryIRepository;
use App\Repository\Interfaces\IInquiryIRepository;
use App\Repository\Interfaces\IPersonalInquiryIRepository;

/**
 * Service class which handles everything about inquiries and its subclasses.
 * @extends  AService<Inquiry, int>
 */
class InquiryService extends AService
{
    protected $companyInquiryRepository;
    protected $personalInquiryRepository;

    public function __construct(IInquiryIRepository         $inquiryRepository, ICompanyInquiryIRepository $companyInquiryRepository,
                                IPersonalInquiryIRepository $personalInquiryRepository)
    {
        parent::__construct($inquiryRepository);
        $this->companyInquiryRepository = $companyInquiryRepository;
        $this->personalInquiryRepository = $personalInquiryRepository;
    }
}