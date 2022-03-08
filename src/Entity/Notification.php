<?php

namespace App\Entity;

use App\Repository\User\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification extends AEntity
{
    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private ?bool $newsletter = true;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="notification", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private ?bool $feedback = true;

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFeedback(): ?bool
    {
        return $this->feedback;
    }

    public function setFeedback(bool $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }
}
