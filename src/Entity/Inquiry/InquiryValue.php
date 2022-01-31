<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\InquiryValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InquiryValueRepository::class)
 */
class InquiryValue
{
    use IdTrait;

    use TitleTrait;

    use OrderingTrait;

    /**
     * @ORM\Column(type="integer")
     */
    protected $value;

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
