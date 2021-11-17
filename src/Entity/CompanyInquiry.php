<?php

namespace App\Entity;

use App\Repository\CompanyInquiryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyInquiryRepository::class)
 */
class CompanyInquiry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=5, max=64)
     */
    private $companyName;

    /**
     * @ORM\OneToOne(targetEntity=Inquiry::class, inversedBy="companyInquiry", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $inquiry;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(Inquiry $inquiry): self
    {
        $this->inquiry = $inquiry;

        return $this;
    }
}
