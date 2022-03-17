<?php

namespace App\Business\Service;

use App\Entity\User\Rating;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\User\IRatingRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<Rating, int>
 */
class RatingService extends AService
{
    /** @var IRatingRepository */
    protected IRepository $repository;

    public function __construct(IRatingRepository $ratingRepository)
    {
        parent::__construct($ratingRepository);
    }
}