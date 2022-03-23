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
 */
#[ORM\Entity(repositoryClass: SupplierRatingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SupplierRating extends User\Rating
{
    #[ORM\ManyToOne(targetEntity: Inquiry::class, inversedBy: "supplierRatings")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inquiry $inquiry;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private ?bool $realizedInquiry = null;

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(Inquiry $inquiry): self
    {
        $this->inquiry = $inquiry;

        return $this;
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
}
