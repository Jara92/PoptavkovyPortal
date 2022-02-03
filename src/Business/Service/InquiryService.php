<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\ICompanyContactRepository;
use App\Repository\Interfaces\IInquiryIRepository;
use App\Repository\Interfaces\IPersonalContactRepository;

/**
 * Service class which handles everything about inquiries and its subclasses.
 * @extends  AService<Inquiry, int>
 */
class InquiryService extends AService
{
    protected $companyInquiryRepository;
    protected $personalInquiryRepository;

    public function __construct(IInquiryIRepository        $inquiryRepository, ICompanyContactRepository $companyInquiryRepository,
                                IPersonalContactRepository $personalInquiryRepository)
    {
        parent::__construct($inquiryRepository);
        $this->companyInquiryRepository = $companyInquiryRepository;
        $this->personalInquiryRepository = $personalInquiryRepository;
    }
}