<?php

namespace App\Business\Service;

use App\Entity\Inquiry\InquirySignedRequest;
use App\Repository\Interfaces\Inquiry\IInquirySignedRequestRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about InquirySignedRequests.
 * @extends  AService<InquirySignedRequest, int>
 */
class InquirySignedRequestService extends AService
{
    /** @var IInquirySignedRequestRepository */
    protected IRepository $repository;

    public function __construct(IInquirySignedRequestRepository $inquirySignedRequestRepository)
    {
        parent::__construct($inquirySignedRequestRepository);
    }
}