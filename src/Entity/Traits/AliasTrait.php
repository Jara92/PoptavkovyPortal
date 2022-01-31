<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait AliasTrait
{
    /**
     * @ORM\Column(type="string", length=70, nullable=false)
     * @Assert\Length(min=2, max=70)
     */
    protected $alias;

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }
}