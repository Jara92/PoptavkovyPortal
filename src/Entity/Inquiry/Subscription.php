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

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->regions = new ArrayCollection();
        $this->types = [];
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
        return $this->types;
    }

    /**
     * @param InquiryType[] $types
     * @return Subscription
     */
    public function setTypes(array $types): Subscription
    {
        $this->types = $types;
        return $this;
    }

    public function addType(InquiryType $type)
    {
        $this->types[] = $type->value;
    }

    public function removeType(InquiryType $type)
    {
        $itemKey = array_search($type->value, $this->types);

        // Remove the item if exists
        if ($itemKey) {
            unset($this->types[$itemKey]);
            // reindex the array.
            $this->types = array_values($this->types);
        }
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
}
