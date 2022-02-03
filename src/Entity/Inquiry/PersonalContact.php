<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\IdTrait;
use App\Repository\PersonalContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonalContactRepository::class)
 */
class PersonalContact
{
    use IdTrait;

    /**
     * @ORM\OneToOne(targetEntity=Inquiry::class, mappedBy="personalContact")
     */
    protected $inquiry;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $surname;

    public function getInquiry()
    {
        return $this->inquiry;
    }

    public function setInquiry($inquiry): self
    {
        $this->inquiry = $inquiry;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
}
