<?php

namespace App\Entity;

use App\Repository\CompanyInquiryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyInquiryRepository::class)
 */
class CompanyInquiry extends Inquiry
{
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=5, max=64)
     */
    private $companyName;

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
