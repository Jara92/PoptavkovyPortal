<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     * @Assert\Length(min=4, max=32)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordering;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

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
