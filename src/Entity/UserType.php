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

    const TYPE_PERSONAL = "personal";
    const TYPE_COMPANY = "company";

    public function is(string $type):bool{
        return ($this->getAlias() === $type);
    }
}
