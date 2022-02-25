<?php

namespace App\Entity\Inquiry;

use App\Entity\AEntity;
use App\Entity\Traits\TitleTrait;
use App\Repository\Inquiry\InquiryAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InquiryAttachmentRepository::class)
 */
class InquiryAttachment extends AEntity
{
    use TitleTrait;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private ?string $hash;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $path;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private ?string $type;

    /**
     * @ORM\ManyToOne(targetEntity=Inquiry::class, inversedBy="attachments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Inquiry $inquiry;

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
}
