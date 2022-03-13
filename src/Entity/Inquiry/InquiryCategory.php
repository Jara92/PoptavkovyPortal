<?php /** @noinspection PhpUnusedAliasInspection */

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\Inquiry\InquiryCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiryCategoryRepository::class)
 */
class InquiryCategory extends AEntity
{
    use TitleTrait;

    use AliasTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=InquiryCategory::class, inversedBy="children")
     */
    protected ?InquiryCategory $parent;

    /**
     * @ORM\OneToMany(targetEntity=InquiryCategory::class, mappedBy="parent", cascade={"persist", "remove"})
     */
    protected Collection $children;

    /**
     * @ORM\ManyToMany(targetEntity=Subscription::class, mappedBy="categories")
     */
    private Collection $subscriptions;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->addCategory($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            $subscription->removeCategory($this);
        }

        return $this;
    }
}
