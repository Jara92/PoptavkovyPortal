<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\DeadlineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DeadlineRepository::class)
 */
class Deadline
{
    use IdTrait;
    use TitleTrait;
    use AliasTrait;
    use OrderingTrait;

    /**
     * @param string $title
     * @param string $alias
     * @return Deadline
     */
    public static function create(string $title, string $alias)
    {
        $deadline = new Deadline();
        $deadline->setTitle($title)->setAlias($alias);

        return $deadline;
    }
}
