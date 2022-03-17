<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\User\UserRepository;

/**
 * User rating.
 * @ORM\Entity(UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class UserRating extends Rating
{

}