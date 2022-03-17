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
 * Inquiry rating created by the inquiring user.
 * @ORM\Entity(repositoryClass=InquiringRatingRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class InquiringRating extends User\Rating
{
    /**
     * @ORM\OneToOne(targetEntity=Inquiry::class, inversedBy="inquiringRating", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Inquiry $inquiry = null;

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(Inquiry $inquiry): self
    {
        $this->inquiry = $inquiry;

        return $this;
    }
}
