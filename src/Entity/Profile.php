<?php

namespace App\Entity;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Repository\User\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
{
    use IdTrait;

   // use AliasTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $avatar;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $web;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $linkedin;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
