<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Entity\Traits\IdTrait;
use App\Entity\User;
use App\Repository\Inquiry\InquirySignedRequestRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InquirySignedRequestRepository::class)]
#[ORM\HasLifecycleCallbacks]
class InquirySignedRequest extends AEntity
{
    #[ORM\PrePersist]
    public function updatedTimestamps(): void
    {
        $now = new DateTime('now');
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($now);
        }
    }

    #[ORM\Column(type: "datetime", nullable: false)]
    private ?DateTime $createdAt = null;

    #[ORM\Column(type: "datetime", nullable: false)]
    private ?DateTime $expireAt = null;

    #[ORM\Column(type: "string", length: 128, nullable: false)]
    private ?string $token;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user;

    #[ORM\ManyToOne(targetEntity: Inquiry::class, inversedBy: "requests")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inquiry $inquiry;

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     * @return InquirySignedRequest
     */
    public function setCreatedAt(?DateTime $createdAt): InquirySignedRequest
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getExpireAt(): ?DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param DateTime|null $expireAt
     * @return InquirySignedRequest
     */
    public function setExpireAt(?DateTime $expireAt): InquirySignedRequest
    {
        $this->expireAt = $expireAt;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param null|string $token
     * @return InquirySignedRequest
     */
    public function setToken(?string $token): InquirySignedRequest
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return Inquiry|null
     */
    public function getInquiry(): ?Inquiry
    {
        return $this->inquiry;
    }

    /**
     * @param Inquiry|null $inquiry
     * @return InquirySignedRequest
     */
    public function setInquiry(?Inquiry $inquiry): InquirySignedRequest
    {
        $this->inquiry = $inquiry;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
