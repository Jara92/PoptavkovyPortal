<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\Traits\TitleTrait;
use App\Entity\User;
use App\Enum\Entity\InquiryType;
use App\Enum\Entity\InquiryState;
use App\Repository\Inquiry\InquiryRepository;
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
    protected string $description;

    /**
     * @ORM\Column (type="string", length=128, nullable=false)
     * @Assert\Length(min=1, max=128)
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected string $contactEmail;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(min=9, max=32)
     */
    protected ?string $contactPhone;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length (min=0, max=64)
     */
    protected ?string $city;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class)
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?Region $region;

    /**
     * @ORM\Column(type="string", enumType=InquiryState::class)
     */
    protected InquiryState $state;

    /**
     * @ORM\ManyToOne(targetEntity=Deadline::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected Deadline $deadline;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryValue::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected InquiryValue $value;

    /**
     * @ORM\ManyToMany(targetEntity=InquiryCategory::class)
     */
    protected Collection $categories;

    /**
     * @ORM\Column(type="string", enumType=InquiryType::class)
     */
    protected InquiryType $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inquiries")
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?User $author;

    /**
     * @ORM\OneToOne(targetEntity=PersonalContact::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private ?PersonalContact $personalContact;

    /**
     * @ORM\OneToOne(targetEntity=CompanyContact::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CompanyContact $companyContact;

    /**
     * @ORM\OneToMany(targetEntity=InquiryAttachment::class, mappedBy="inquiry", orphanRemoval=true)
     */
    private Collection $attachments;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="inquiry", orphanRemoval=true)
     */
    private Collection $offers;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContactEmail(): string
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getState(): InquiryState
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
     * @return Collection
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

    public function getType(): InquiryType
    {
        return $this->type;
    }

    public function setType(InquiryType $type): self
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

    public function getPersonalContact(): ?PersonalContact
    {
        return $this->personalContact;
    }

    public function setPersonalContact(?PersonalContact $personalContact): self
    {
        $this->personalContact = $personalContact;

        return $this;
    }

    public function getCompanyContact(): ?CompanyContact
    {
        return $this->companyContact;
    }

    public function setCompanyContact(?CompanyContact $companyContact): self
    {
        $this->companyContact = $companyContact;

        return $this;
    }

    public function isType(InquiryType $type): bool
    {
        return $this->getType() == $type;
    }

    /**
     * @return Collection|InquiryAttachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(InquiryAttachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setInquiry($this);
        }

        return $this;
    }

    public function removeAttachment(InquiryAttachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getInquiry() === $this) {
                $attachment->setInquiry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setInquiry($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getInquiry() === $this) {
                $offer->setInquiry(null);
            }
        }

        return $this;
    }
}
