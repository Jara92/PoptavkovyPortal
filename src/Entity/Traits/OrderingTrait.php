<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait OrderingTrait
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $ordering;

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): self
    {
        $this->ordering = $ordering;

        return $this;
    }
}