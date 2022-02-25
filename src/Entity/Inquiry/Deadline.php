<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\Inquiry\DeadlineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeadlineRepository::class)
 */
class Deadline extends AEntity
{
    use TitleTrait;

    use AliasTrait;

    use OrderingTrait;
}
