<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\IInquiryIRepository;
use App\Repository\Interfaces\IRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Service class which handles everything about inquiries and its subclasses.
 * @extends  AService<Inquiry, int>
 */
class InquiryService extends AService
{
    /** @var IInquiryIRepository  */
    protected IRepository $repository;

    public function __construct(IInquiryIRepository $inquiryRepository)
    {
        parent::__construct($inquiryRepository);
    }
}