<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\RegionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
class Region
{
    use IdTrait;

    use TitleTrait;

    use OrderingTrait;
}
