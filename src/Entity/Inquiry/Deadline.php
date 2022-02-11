<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\DeadlineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeadlineRepository::class)
 */
class Deadline
{
    use IdTrait;

    use TitleTrait;

    use AliasTrait;

    use OrderingTrait;
}
