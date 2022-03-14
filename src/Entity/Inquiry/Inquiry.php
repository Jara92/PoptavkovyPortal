<?php

namespace App\Entity\Inquiry;

use App\Business\SmartTag\ISmartTag;
use App\Entity\AEntity;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\HitsTrait;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\Traits\TitleTrait;
use App\Entity\User;
use App\Enum\Entity\InquiryType;
use App\Enum\Entity\InquiryState;
use App\Repository\Inquiry\InquiryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\Constraint as AppAssert;

/**
 * Class Inquiry
 * @ORM\Entity(repositoryClass=InquiryRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="inquiry", indexes={@ORM\Index(columns={"title", "description", "city"}, flags={"fulltext"})})
 */
class Inquiry extends AEntity
{
    use TimeStampTrait;

    use TitleTrait;

    use AliasTrait;

    use HitsTrait;

    /**
     * @ORM\Column(type="text",  nullable=false)
     * @Assert\Length(min=20)
     */
    private ?string $description = null;

    /**
     * @ORM\Column (type="string", length=128, nullable=false)
     * @Assert\Length(min=1, max=128)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private ?string $contactEmail = null;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(min=9, max=32)
     * @AppAssert\PhoneNumber()
     */
    private ?string $contactPhone = null;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length (min=0, max=64)
     */
    private ?string $city = null;

    /**
     * TODO: Index region somehow.
     * @ORM\ManyToOne(targetEntity=Region::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Region $region = null;

    /**
     * @ORM\Column(type="string", enumType=InquiryState::class)
     */
    private ?InquiryState $state = null;

    /**
     * @ORM\ManyToOne(targetEntity=Deadline::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Deadline $deadline = null;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $deadlineText = null;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryValue::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?InquiryValue $value = null;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $valueText = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $valueNumber = null;

    /**
     * TODO: Index categorie's titles somehow.
     * @ORM\ManyToMany(targetEntity=InquiryCategory::class)
     */
    private Collection $categories;

    /**
     * @ORM\Column(type="string", enumType=InquiryType::class)
     */
    private ?InquiryType $type = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inquiries")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $author = null;

    /**
     * @ORM\OneToOne(targetEntity=PersonalContact::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private ?PersonalContact $personalContact = null;

    /**
     * @ORM\OneToOne(targetEntity=CompanyContact::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CompanyContact $companyContact = null;

    /**
     * @ORM\OneToMany(targetEntity=InquiryAttachment::class, mappedBy="inquiry", orphanRemoval=true)
     */
    private Collection $attachments;

    /**
     * @var ISmartTag[]
     */
    private array $smartTags = [];

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="inquiry", orphanRemoval=true)
     */
    private Collection $offers;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $publishedAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $removeNoticeAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $removeAt = null;

    /**
     * @ORM\OneToOne(targetEntity=InquiringRating::class, mappedBy="inquiry", cascade={"persist", "remove"})
     */
    private ?InquiringRating $inquiringRating = null;

    /**
     * @ORM\OneToMany(targetEntity=SupplierRating::class, mappedBy="inquiry", orphanRemoval=true)
     */
    private Collection $supplierRatings;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->supplierRatings = new ArrayCollection();
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

    public function getDeadlineText(): ?string
    {
        return $this->deadlineText;
    }

    public function setDeadlineText(?string $deadlineText): self
    {
        $this->deadlineText = $deadlineText;

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

    public function getValueText(): ?string
    {
        return $this->valueText;
    }

    public function setValueText(?string $valueText): self
    {
        $this->valueText = $valueText;

        return $this;
    }

    public function getValueNumber(): ?int
    {
        return $this->valueNumber;
    }

    public function setValueNumber(?int $valueNumber): self
    {
        $this->valueNumber = $valueNumber;

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
     * @return Collection
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
     * @return ISmartTag[]
     */
    public function getSmartTags(): array
    {
        return $this->smartTags;
    }

    /**
     * @param ISmartTag[] $smartTags
     * @return Inquiry
     */
    public function setSmartTags(array $smartTags): Inquiry
    {
        $this->smartTags = $smartTags;
        return $this;
    }

    public function addSmartTag(ISmartTag $smartTag): Inquiry
    {
        $this->smartTags[] = $smartTag;
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

    /**
     * @return DateTime|null
     */
    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @param DateTime|null $publishedAt
     * @return Inquiry
     */
    public function setPublishedAt(?DateTime $publishedAt): Inquiry
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getRemoveNoticeAt(): ?Datetime
    {
        return $this->removeNoticeAt;
    }

    public function setRemoveNoticeAt(?Datetime $removeNoticeAt): self
    {
        $this->removeNoticeAt = $removeNoticeAt;

        return $this;
    }

    public function getRemoveAt(): ?Datetime
    {
        return $this->removeAt;
    }

    public function setRemoveAt(?Datetime $removeAt): self
    {
        $this->removeAt = $removeAt;

        return $this;
    }

    public function getInquiringRating(): ?InquiringRating
    {
        return $this->inquiringRating;
    }

    public function setInquiringRating(InquiringRating $inquiringRating): self
    {
        // set the owning side of the relation if necessary
        if ($inquiringRating->getInquiry() !== $this) {
            $inquiringRating->setInquiry($this);
        }

        $this->inquiringRating = $inquiringRating;

        return $this;
    }

    /**
     * @return Collection<int, SupplierRating>
     */
    public function getSupplierRatings(): Collection
    {
        return $this->supplierRatings;
    }

    public function addSupplierRating(SupplierRating $supplierRating): self
    {
        if (!$this->supplierRatings->contains($supplierRating)) {
            $this->supplierRatings[] = $supplierRating;
            $supplierRating->setInquiry($this);
        }

        return $this;
    }

    public function removeSupplierRating(SupplierRating $supplierRating): self
    {
        if ($this->supplierRatings->removeElement($supplierRating)) {
            // set the owning side to null (unless already changed)
            if ($supplierRating->getInquiry() === $this) {
                $supplierRating->setInquiry(null);
            }
        }

        return $this;
    }
}
