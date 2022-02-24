<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Offer;
use App\Repository\Interfaces\Inquiry\IOfferRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<Offer, int>
 */
class OfferService extends AService
{
    /** @var IOfferRepository */
    protected IRepository $repository;

    public function __construct(IOfferRepository $offerRepository)
    {
        parent::__construct($offerRepository);
    }
}