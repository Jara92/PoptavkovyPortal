<?php

namespace App\Business\Service\Inquiry\Rating;

use App\Business\Service\AService;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Repository\Interfaces\Inquiry\Rating\IInquiringRatingRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about InquiringInquiryRating.
 * @extends  AService<InquiringRating, int>
 */
class InquiringRatingService extends AService
{
    /** @var IInquiringRatingRepository */
    protected IRepository $repository;

    public function __construct(IInquiringRatingRepository $ratingRepository)
    {
        parent::__construct($ratingRepository);
    }
}