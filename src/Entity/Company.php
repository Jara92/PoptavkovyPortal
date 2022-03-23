<?php

namespace App\Entity;

use App\Repository\User\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company extends AEntity
{
    #[ORM\Column(type: "string", length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 32)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    private ?string $identificationNumber = null;

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    #[Assert\Length(max: 32)]
    private ?string $taxIdentificationNumber = null;

    #[ORM\Column(type: "string", length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $addressStreet = null;

    #[ORM\Column(type: "string", length: 32)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    private ?string $addressCity = null;

    #[ORM\Column(type: "string", length: 16)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 16)]
    private ?string $addressZipCode = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdentificationNumber(): ?string
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(string $identificationNumber): self
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    public function getTaxIdentificationNumber(): ?string
    {
        return $this->taxIdentificationNumber;
    }

    public function setTaxIdentificationNumber(string $taxIdentificationNumber): self
    {
        $this->taxIdentificationNumber = $taxIdentificationNumber;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->addressStreet;
    }

    public function setAddressStreet(string $addressStreet): self
    {
        $this->addressStreet = $addressStreet;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->addressCity;
    }

    public function setAddressCity(string $addressCity): self
    {
        $this->addressCity = $addressCity;

        return $this;
    }

    public function getAddressZipCode(): ?string
    {
        return $this->addressZipCode;
    }

    public function setAddressZipCode(string $addressZipCode): self
    {
        $this->addressZipCode = $addressZipCode;

        return $this;
    }
}
