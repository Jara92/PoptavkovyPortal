<?php

namespace App\Repository\Interfaces\User;

use App\Entity\User;
use App\Entity\User\Rating;
use App\Repository\Interfaces\IRepository;

/**
 * @implements IRepository<Rating, int>
 */
interface IRatingRepository extends IRepository
{
    /**
     * Returns average rating for the given target.
     * @param User $user
     * @return float
     */
    public function getAverageRatingForTarget(User $user): float;

    /**
     * Returns array [rating_value => number of this value]
     * @param User $user
     * @return array
     */
    public function getRatingValuesCount(User $user): array;
}