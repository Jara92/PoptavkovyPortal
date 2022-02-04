<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\IdTrait;
use App\Repository\CompanyContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyContactRepository::class)
 */
class CompanyContact
{
    use IdTrait;

    /**
     * @ORM\OneToOne(targetEntity=Inquiry::class, mappedBy="companyContact")
     */
    protected $inquiry;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=5, max=64)
     */
    protected string $companyName;

    public function getInquiry()
    {
        return $this->inquiry;
    }

    public function setInquiry($inquiry): self
    {
        $this->inquiry = $inquiry;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }
}
