<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * This trait contains base timestamps createdAt and updatedAt.
 * There values are automatically updated.
 * Derived entities must have @ORM\HasLifecycleCallbacks annotation.
 * @ORM\HasLifecycleCallbacks
 */
trait TimeStampTrait
{
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $now = new DateTime('now');
        $this->setUpdatedAt($now);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($now);
        }
    }

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @return mixed
     */
    public function getCreatedAt(): mixed
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return \App\Entity\User|\App\Entity\Inquiry\Inquiry|TimeStampTrait
     */
    public function setCreatedAt(mixed $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt(): mixed
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return \App\Entity\User|\App\Entity\Inquiry\Inquiry|TimeStampTrait
     */
    public function setUpdatedAt(mixed $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}