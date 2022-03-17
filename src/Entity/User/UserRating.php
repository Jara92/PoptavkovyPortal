<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * User rating.
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class UserRating extends Rating
{

}