<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Entity\Traits\IdTrait;
use App\Entity\User;
use App\Enum\Entity\InquiryType;
use App\Repository\Inquiry\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription extends AEntity
{
    /**
     * @ORM\ManyToMany(targetEntity=InquiryCategory::class, inversedBy="subscriptions")
     */
    private Collection $categories;

    /**
     * @ORM\ManyToMany(targetEntity=Region::class, inversedBy="subscriptions")
     */
    private Collection $regions;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="subscription", cascade={"persist", "remove"})
     */
    private ?User $user;

    /**
     * @ORM\Column(type="json")
     */
    private array $types;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private ?bool $newsletter;

    /**
     * @ORM\ManyToMany(targetEntity=Inquiry::class)
     */
    private $inquiries;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->regions = new ArrayCollection();
        $this->types = [];
        $this->inquiries = new ArrayCollection();
    }

    /**
     * @return Collection<int, InquiryCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param InquiryCategory[] $categories
     * @return $this
     */
    public function setCategories(array $categories): self
    {
        $this->regions->clear();
        foreach ($categories as $category) {
            $this->addCategory($category);
        }
        return $this;
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

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    /**
     * @param Region[] $regions
     * @return $this
     */
    public function setRegions(array $regions): self
    {
        $this->regions->clear();
        foreach ($regions as $region) {
            $this->addRegion($region);
        }
        return $this;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions[] = $region;
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        $this->regions->removeElement($region);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getSubscription() !== $this) {
            $user->setSubscription($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return InquiryType[]
     */
    public function getTypes(): array
    {
        // Convert string[] to InquiryType[]
        return array_map(fn(string $value) => InquiryType::tryFrom($value), $this->types);
    }

    /**
     * @param InquiryType[] $types
     * @return Subscription
     */
    public function setTypes(array $types): Subscription
    {
        // Convert InquiryType[] to string[]
        $this->types = array_map(fn(InquiryType $type) => $type->value, $types);
        return $this;
    }

    public function addType(InquiryType $type): Subscription
    {
        $this->types[] = $type->value;

        return $this;
    }

    public function removeType(InquiryType $type): Subscription
    {
        $itemKey = array_search($type->value, $this->types);

        // Remove the item if exists
        if ($itemKey >= 0) {
            unset($this->types[$itemKey]);
            // reindex the array.
            $this->types = array_values($this->types);
        }

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return Collection<int, Inquiry>
     */
    public function getInquiries(): Collection
    {
        return $this->inquiries;
    }

    public function addInquiry(Inquiry $inquiry): self
    {
        if (!$this->inquiries->contains($inquiry)) {
            $this->inquiries[] = $inquiry;
        }

        return $this;
    }

    public function removeInquiry(Inquiry $inquiry): self
    {
        $this->inquiries->removeElement($inquiry);

        return $this;
    }

    public function clearInquiries(): self
    {
        $this->inquiries->clear();

        return $this;
    }
}
