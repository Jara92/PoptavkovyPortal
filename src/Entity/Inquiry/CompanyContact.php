<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Repository\Inquiry\CompanyContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyContactRepository::class)
 */
class CompanyContact extends AEntity
{
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=5, max=64)
     */
    protected ?string $companyName;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank
     */
    private ?string $identificationNumber;

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

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
}
