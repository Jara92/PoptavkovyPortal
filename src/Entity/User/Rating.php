<?php

namespace App\Entity\User;

use App\Entity\AEntity;
use App\Entity\Traits\TimeStampTrait;
use App\Entity\User;
use App\Repository\User\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"inquiring" = "App\Entity\Inquiry\Rating\InquiringRating", "supplier" = "App\Entity\Inquiry\Rating\SupplierRating"})
 * @ORM\HasLifecycleCallbacks
 */
class Rating extends AEntity
{
    use TimeStampTrait;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="myRatings")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $author = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $authorName = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $target = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min="1", max="5")
     */
    private ?int $rating = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $targetNote = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $note = null;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private ?bool $isPublished = false;

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * @return Rating
     */
    public function setAuthor(?User $author): Rating
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * @param null|string $authorName
     * @return Rating
     */
    public function setAuthorName(?string $authorName): Rating
    {
        $this->authorName = $authorName;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getTarget(): ?User
    {
        return $this->target;
    }

    /**
     * @param User|null $target
     * @return Rating
     */
    public function setTarget(?User $target): Rating
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int|null $rating
     * @return Rating
     */
    public function setRating(?int $rating): Rating
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTargetNote(): ?string
    {
        return $this->targetNote;
    }

    /**
     * @param null|string $targetNote
     * @return Rating
     */
    public function setTargetNote(?string $targetNote): Rating
    {
        $this->targetNote = $targetNote;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param null|string $note
     * @return Rating
     */
    public function setNote(?string $note): Rating
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool|null $isPublished
     * @return Rating
     */
    public function setIsPublished(?bool $isPublished): Rating
    {
        $this->isPublished = $isPublished;
        return $this;
    }


}
