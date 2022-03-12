<?php

namespace App\Business\Service\Inquiry\Rating;

use App\Business\Service\AService;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Repository\Interfaces\Inquiry\Rating\ISupplierRatingRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about InquiringInquiryRating.
 * @extends  AService<SupplierRating, int>
 */
class SupplierRatingService extends AService
{
    /** @var ISupplierRatingRepository */
    protected IRepository $repository;

    public function __construct(ISupplierRatingRepository $ratingRepository)
    {
        parent::__construct($ratingRepository);
    }
}