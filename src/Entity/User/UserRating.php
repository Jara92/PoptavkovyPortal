<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\User\UserRatingRepository;

/**
 * User rating.
 */
#[ORM\Entity(UserRatingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserRating extends Rating
{

}