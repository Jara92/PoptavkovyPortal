<?php

namespace App\Entity;

use App\Repository\InquiryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**

 */
/**
 * Class Inquiry
 * @ORM\Entity(repositoryClass=InquiryRepository::class)
 * @ORM\InheritanceType(value="JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=16)
 * @ORM\DiscriminatorMap({
 *     "inquiry" = "Inquiry",
 *     "company-inquiry" = "CompanyInquiry",
 *     "personal-inquiry" = "PersonalInquiry"
 *     })
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
     * @ORM\Column (type="string", length=64, nullable=false)
     * @Assert\Length(min=4, max=64)
     * @Assert\NotBlank
     */
    protected $title;

    /**
     * @ORM\Column (type="string", length=64, nullable=false)
     * @Assert\Length(min=1, max=64)
     * @Assert\NotBlank
     */
    protected $alias;

    /**
     * @ORM\Column(type="text",  nullable=false)
     */
    protected $description;

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
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length (min=0, max=64)
     */
    private $city;

    /**
     * @ORM\OneToOne(targetEntity=PersonalInquiry::class, mappedBy="inquiry", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $personalInquiry;

    /**
     * @ORM\OneToOne(targetEntity=CompanyInquiry::class, mappedBy="inquiry", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $companyInquiry;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryState::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Deadline::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $deadline;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryValue::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $value;

    /**
     * @ORM\ManyToMany(targetEntity=InquiryCategory::class)
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPersonalInquiry(): ?PersonalInquiry
    {
        return $this->personalInquiry;
    }

    public function setPersonalInquiry(PersonalInquiry $personalInquiry): self
    {
        // set the owning side of the relation if necessary
        if ($personalInquiry->getInquiry() !== $this) {
            $personalInquiry->setInquiry($this);
        }

        $this->personalInquiry = $personalInquiry;

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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getState(): ?InquiryState
    {
        return $this->state;
    }

    public function setState(?InquiryState $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getDeadline(): ?Deadline
    {
        return $this->deadline;
    }

    public function setDeadline(?Deadline $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getValue(): ?InquiryValue
    {
        return $this->value;
    }

    public function setValue(?InquiryValue $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|InquiryCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(InquiryCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(InquiryCategory $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getType(): ?InquiryType
    {
        return $this->type;
    }

    public function setType(?InquiryType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
