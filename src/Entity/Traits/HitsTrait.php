<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HitsTrait
{
    /**
     * @ORM\Column(type="integer", length=70, options={"default"=0} )
     */
    protected int $hits = 0;

    public function getHits(): ?int
    {
        return $this->hits;
    }

    public function setHits(int $hits): self
    {
        $this->hits = $hits;

        return $this;
    }

    public function incHits(): self
    {
        $this->hits++;

        return $this;
    }
}