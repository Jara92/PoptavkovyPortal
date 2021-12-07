<?php

namespace App\Entity;

use App\Repository\InquiryTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiryTypeRepository::class)
 */
class InquiryType
{
   const ALIAS_PERSONAL = "personal";
   const ALIAS_COMPANY = "company";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=4, max=64)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\Length(min=4, max=32)
     */
    private $alias;

    /**
     * @param string $title
     * @param string $alias
     * @return InquiryType
     */
    public static function create(string $title, string $alias)
    {
        $type = new InquiryType();
        $type->setTitle($title)->setAlias($alias);

        return $type;
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }
}
