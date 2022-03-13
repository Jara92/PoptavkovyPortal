<?php

namespace App\Entity\Inquiry\Rating;

use App\Entity\AEntity;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\User;
use App\Repository\Inquiry\Rating\SupplierRatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Inquiry rating created the inquiry supplier.
 * @ORM\Entity(repositoryClass=SupplierRatingRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class SupplierRating extends AEntity
{
    use TimeStampTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Inquiry::class, inversedBy="supplierRatings")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Inquiry $inquiry;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author = null;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private ?bool $realizedInquiry = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min="1", max="5")
     */
    private ?int $rating = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $inquiringNote = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $note = null;

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(Inquiry $inquiry): self
    {
        $this->inquiry = $inquiry;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function getRealizedInquiry(): ?bool
    {
        return $this->realizedInquiry;
    }

    public function setRealizedInquiry(?bool $realizedInquiry): self
    {
        $this->realizedInquiry = $realizedInquiry;
        return $this;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getInquiringNote(): ?string
    {
        return $this->inquiringNote;
    }

    public function setInquiringNote(?string $inquiringNote): self
    {
        $this->inquiringNote = $inquiringNote;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }
}
