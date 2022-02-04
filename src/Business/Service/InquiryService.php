<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\IInquiryIRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Service class which handles everything about inquiries and its subclasses.
 * @extends  AService<Inquiry, int>
 */
class InquiryService extends AService
{
    protected $companyInquiryRepository;
    protected $personalInquiryRepository;

    public function __construct(IInquiryIRepository $inquiryRepository, ManagerRegistry $doctrine)
    {
        parent::__construct($inquiryRepository, $doctrine);
    }
}