<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\User;
use App\Repository\Inquiry\OfferRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Offer extends AEntity
{
    use TimeStampTrait;

    #[ORM\Column(type: "text")]
    #[Assert\Length(min: 20)]
    private ?string $text;

    #[ORM\ManyToOne(targetEntity: Inquiry::class, inversedBy: "offers")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inquiry $inquiry;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "offers")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    public function setInquiry(?Inquiry $inquiry): self
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
}
