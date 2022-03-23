<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Repository\Inquiry\PersonalContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalContactRepository::class)]
class PersonalContact extends AEntity
{
    #[ORM\Column(type: "string", length: 64)]
    protected string $name;

    #[ORM\Column(type: "string", length: 64)]
    protected string $surname;

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
