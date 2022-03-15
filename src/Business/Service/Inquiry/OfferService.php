<?php

namespace App\Business\Service\Inquiry;

use App\Business\Service\AService;
use App\Entity\Inquiry\Inquiry;
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
    public function readByAuthor(User $author, PaginationData $paginationData = null, array $ordering = ["id" => "desc"]): array
    {
        return $this->repository->findByAuthor($author, $paginationData, $ordering);
    }

    /**
     * Returns an offer by inquiry and user.
     * There are exactly 0 or 1 offer
     * @param Inquiry $inquiry
     * @param User $user
     * @return ?Offer
     */
    public function readOneByInquiryAndAuthor(Inquiry $inquiry, User $user): ?Offer
    {
        return $this->repository->findOneBy(["inquiry" => $inquiry, "author" => $user]);
    }
}