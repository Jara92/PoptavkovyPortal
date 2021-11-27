<?php

namespace App\Entity;

use App\Repository\InquiryValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InquiryValueRepository::class)
 */
class InquiryValue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @param string $title
     * @param int $value
     * @return InquiryValue
     */
    public static function create(string $title, int $value)
    {
        $inquiryValue = new InquiryValue();
        return $inquiryValue->setTitle($title)->setValue($value);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
