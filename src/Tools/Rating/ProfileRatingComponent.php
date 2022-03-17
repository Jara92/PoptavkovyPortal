<?php

namespace App\Tools\Rating;

use App\Entity\User;
use App\Entity\User\Rating;
use App\Enum\ERating;

class ProfileRatingComponent
{
    private User $user;

    private float $avgRating;

    /** @var array [ERating->value => int] */
    private array $ratingDistinctCounts;

    private int $ratingsCount;

    /** @var Rating[] */
    private array $ratings = [];

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ProfileRatingComponent
     */
    public function setUser(User $user): ProfileRatingComponent
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return float
     */
    public function getAvgRating(): float
    {
        return $this->avgRating;
    }

    /**
     * @param float $avgRating
     * @return ProfileRatingComponent
     */
    public function setAvgRating(float $avgRating): ProfileRatingComponent
    {
        $this->avgRating = $avgRating;
        return $this;
    }

    /**
     * @return array
     */
    public function getRatingDistinctCounts(): array
    {
        return $this->ratingDistinctCounts;
    }

    /**
     * @param array $ratingDistinctCounts
     * @return ProfileRatingComponent
     */
    public function setRatingDistinctCounts(array $ratingDistinctCounts): ProfileRatingComponent
    {
        $this->ratingDistinctCounts = $ratingDistinctCounts;
        return $this;
    }

    /**
     * @return int
     */
    public function getRatingsCount(): int
    {
        return $this->ratingsCount;
    }

    /**
     * @param int $ratingsCount
     * @return ProfileRatingComponent
     */
    public function setRatingsCount(int $ratingsCount): ProfileRatingComponent
    {
        $this->ratingsCount = $ratingsCount;
        return $this;
    }

    /**
     * @return Rating[]
     */
    public function getRatings(): array
    {
        return $this->ratings;
    }

    /**
     * @param Rating[] $ratings
     * @return ProfileRatingComponent
     */
    public function setRatings(array $ratings): ProfileRatingComponent
    {
        $this->ratings = $ratings;
        return $this;
    }


}