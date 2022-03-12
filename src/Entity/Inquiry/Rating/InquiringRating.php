<?php

namespace App\Entity\Inquiry\Rating;

use App\Entity\AEntity;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\User;
use App\Repository\Inquiry\Rating\InquiringRatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiringRatingRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class InquiringRating extends AEntity
{
    use TimeStampTrait;

    /**
     * @ORM\OneToOne(targetEntity=Inquiry::class, inversedBy="inquiringRating", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Inquiry $inquiry;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ratings")
     */
    private ?User $supplier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min="1", max="5")
     */
    private ?int $rating;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $supplierNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $note;

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(Inquiry $inquiry): self
    {
        $this->inquiry = $inquiry;

        return $this;
    }

    public function getSupplier(): ?User
    {
        return $this->supplier;
    }

    public function setSupplier(?User $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getSupplierNote(): ?string
    {
        return $this->supplierNote;
    }

    public function setSupplierNote(?string $supplierNote): self
    {
        $this->supplierNote = $supplierNote;

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
