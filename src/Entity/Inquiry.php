<?php

namespace App\Entity;

use App\Repository\InquiryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiryRepository::class)
 */
class Inquiry
{
    use TimeStampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="text",  nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column (type="string", length=64, nullable=false)
     * @Assert\Length(min=1, max=64)
     * @Assert\NotBlank
     */
    protected $alias;

    /**
     * @ORM\Column (type="string", length=128, nullable=false)
     * @Assert\Length(min=1, max=128)
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected $contactEmail;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(min=9, max=32)
     */
    private $contactPhone;

    /**
     * @ORM\OneToOne(targetEntity=CompanyInquiry::class, mappedBy="inquiry", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, unique=true)
     */
    private $companyInquiry;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length (min=0, max=64)
     */
    private $city;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $contactEmail): self
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): self
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getCompanyInquiry(): ?CompanyInquiry
    {
        return $this->companyInquiry;
    }

    public function setCompanyInquiry(CompanyInquiry $companyInquiry): self
    {
        // set the owning side of the relation if necessary
        if ($companyInquiry->getInquiry() !== $this) {
            $companyInquiry->setInquiry($this);
        }

        $this->companyInquiry = $companyInquiry;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
