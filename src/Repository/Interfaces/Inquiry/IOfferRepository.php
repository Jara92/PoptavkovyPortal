<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\Offer;
use App\Entity\User;
use App\Repository\Interfaces\IRepository;
use App\Tools\Pagination\PaginationData;

/**
 * @implements IRepository<Offer, int>
 */
interface IOfferRepository extends IRepository
{
    /**
     * Returns all offers owned by given user.
     * @param User $author
     * @param PaginationData|null $data
     * @param array $ordering
     * @return array
     */
    public function findByAuthor(User $author, PaginationData $data = null, array $ordering = []): array;
}