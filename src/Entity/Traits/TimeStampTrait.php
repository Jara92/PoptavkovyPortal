<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * This trait contains base timestamps createdAt and updatedAt.
 * There values are automatically updated.
 * Derived entities must have ORM\HasLifecycleCallbacks annotation.
 */
#[ORM\HasLifecycleCallbacks]
trait TimeStampTrait
{
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $now = new DateTime('now');
        $this->setUpdatedAt($now);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($now);
        }
    }

    #[ORM\Column(type: "datetime", nullable: false)]
    protected ?DateTime $createdAt = null;

    #[ORM\Column(type: "datetime", nullable: false)]
    protected ?DateTime $updatedAt = null;

    /**
     * @return ?DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param ?DateTime $createdAt
     * @return \App\Entity\User|\App\Entity\Inquiry\Inquiry|TimeStampTrait
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param ?DateTime $updatedAt
     * @return \App\Entity\User|\App\Entity\Inquiry\Inquiry|TimeStampTrait
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}