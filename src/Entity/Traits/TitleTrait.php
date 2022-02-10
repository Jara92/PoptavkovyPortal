<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TitleTrait
{
    public function __toString(){return $this->getTitle();}

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\Length(min=2, max=64)
     */
    protected ?string $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}