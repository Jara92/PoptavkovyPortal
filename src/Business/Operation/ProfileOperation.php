<?php

namespace App\Business\Operation;

use App\Business\Service\RatingService;
use App\Entity\Profile;
use App\Tools\Rating\ProfileRatingComponent;

class ProfileOperation
{
    public function __construct(
        private RatingService $ratingService,
    )
    {
    }

    /**
     * Returns rating for the given profile.
     * @param Profile $profile
     * @return ProfileRatingComponent
     */
    public function getProfileRating(Profile $profile): ProfileRatingComponent
    {
        $all = $this->ratingService->readAllPublicForTarget($profile->getUser());
        $avgRating = $this->ratingService->getAverageRatingForTarget($profile->getUser());
        $ratingsCount = $this->ratingService->getRatingsCount($profile->getUser());

        return (new ProfileRatingComponent())->setRatings($all)->setAvgRating($avgRating)->setRatingsCount(count($all))
            ->setRatingDistinctCounts($ratingsCount)->setUser($profile->getUser());
    }
}