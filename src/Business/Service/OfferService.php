<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Offer;
use App\Entity\User;
use App\Repository\Interfaces\Inquiry\IOfferRepository;
use App\Repository\Interfaces\IRepository;
use App\Tools\Pagination\PaginationData;

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

    /**
     * Returns given user's offers.
     * @param User $author
     * @param PaginationData|null $paginationData
     * @param array $ordering
     * @return array
     */
    public function readByAuthor(User $author, PaginationData $paginationData = null, array $ordering = []): array
    {
        return $this->repository->findByAuthor($author, $paginationData);
    }
}