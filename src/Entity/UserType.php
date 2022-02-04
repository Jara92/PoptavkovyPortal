<?php

namespace App\Entity;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\UserTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTypeRepository::class)
 */
class UserType
{
    use IdTrait;

    use TitleTrait;

    use AliasTrait;
}
