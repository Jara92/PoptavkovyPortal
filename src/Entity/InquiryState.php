<?php

namespace App\Entity;

use App\Repository\InquiryStateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiryStateRepository::class)
 */
class InquiryState
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=2, max=64)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     * @Assert\Length(min=1, max=16)
     * @Assert\Unique
     */
    private $alias;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $ordering;

    /**
     * @param string $title
     * @param string $alias
     * @param string $description
     * @param int $ordering
     * @return InquiryState
     */
    public static function create(string $title, string $alias, string $description, int $ordering)
    {
        $state = new InquiryState();
        $state->setTitle($title)->setAlias($alias)->setDescription($description)->setOrdering($ordering);

        return $state;
    }


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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
