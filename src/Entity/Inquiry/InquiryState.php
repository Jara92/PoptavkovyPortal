<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\Inquiry\InquiryStateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiryStateRepository::class)
 */
class InquiryState
{
    use IdTrait;

    use TitleTrait;

    use AliasTrait;

    use OrderingTrait;

    const STATE_NEW = "new";
    const STATE_ACTIVE = "active";
    const STATE_PROCESSING = "processing";
    const STATE_ARCHIVED = "archived";
    const STATE_DELETED = "deleted";

    /**
     * @ORM\Column(type="string", length=16, unique=true, nullable=true)
     * @Assert\Length(min=1, max=16)
     */
    protected ?string $alias;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected ?string $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
