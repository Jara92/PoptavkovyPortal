<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\Traits\TitleTrait;
use App\Entity\User;
use App\Repository\InquiryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Inquiry
 * @ORM\Entity(repositoryClass=InquiryRepository::class)
 * @ORM\HasLifecycleCallbacks 
 */
class Inquiry
{
    use TimeStampTrait;

    use IdTrait;

    use TitleTrait;

    use AliasTrait;

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
    protected $contactPhone;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length (min=0, max=64)
     */
    protected $city;

    /**
     * @ORM\OneToOne(targetEntity=PersonalContact::class, inversedBy="inquiry", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $personalContact;

    /**
     * @ORM\OneToOne(targetEntity=CompanyContact::class, inversedBy="inquiry", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $companyContact;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryState::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $state;

    /**
     * @ORM\ManyToOne(targetEntity=Deadline::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $deadline;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryValue::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $value;

    /**
     * @ORM\ManyToMany(targetEntity=InquiryCategory::class)
     */
    protected $categories;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inquiries")
     */
    protected ?User $author;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getPersonalContact(): ?PersonalContact
    {
        return $this->personalContact;
    }

    public function setPersonalContact(?PersonalContact $personalContact): self
    {
        // set the owning side of the relation if necessary
        if ($personalContact !== null && $personalContact->getInquiry() !== $this) {
        //    $personalContact->setInquiry($this);
        }

        $this->personalContact = $personalContact;

        return $this;
    }

    public function getCompanyContact(): ?CompanyContact
    {
        return $this->companyContact;
    }

    public function setCompanyContact(?CompanyContact $companyContact): self
    {
        // set the owning side of the relation if necessary
        if ($companyContact !== null && $companyContact->getInquiry() !== $this) {
            //$companyContact->setInquiry($this);
        }

        $this->companyContact = $companyContact;

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

    public function setState(InquiryState $state): self
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
