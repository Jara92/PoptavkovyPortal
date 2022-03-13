<?php

namespace App\Entity;

use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\Offer;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Entity\Inquiry\Subscription;
use App\Entity\Traits\TimeStampTrait;
use App\Enum\Entity\UserRole;
use App\Enum\Entity\UserType;
use App\Repository\User\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\Constraint as AppAssert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="email_already_registred")
 * @ORM\HasLifecycleCallbacks
 */
class User extends AEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimeStampTrait;

    public function __toString()
    {
        $value = $this->id . ": ";

        if ($this->type == UserType::PERSON) {
            $value .= $this->person->getName() . " " . $this->person->getSurname();
        } else if ($this->type == UserType::COMPANY) {
            $value .= $this->company->getName();
        }

        return $value . " [" . $this->createdAt->format("d.m.Y") . "]";
    }

    /**
     * Contact email
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     */
    protected ?string $email;

    /**
     * Contact phone number.
     * @ORM\Column(type="string", length=32, nullable=true)
     * @AppAssert\PhoneNumber()
     */
    protected ?string $phone;

    /**
     * @ORM\Column(type="json")
     */
    protected array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected string $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTime $emailVerifiedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTime $lastEmailVerificationTry;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Inquiry::class, mappedBy="author")
     */
    protected Collection $inquiries;

    /**
     * @ORM\Column(type="string", enumType=UserType::class)
     */
    private ?UserType $type = null;

    /**
     * @ORM\OneToOne(targetEntity=Person::class, cascade={"persist", "remove"})
     */
    private ?Person $person = null;

    /**
     * @ORM\OneToOne(targetEntity=Company::class, cascade={"persist", "remove"})
     */
    private ?Company $company = null;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Profile $profile = null;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="author", orphanRemoval=true)
     */
    private Collection $offers;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private ?Subscription $subscription = null;

    /**
     * @ORM\OneToOne(targetEntity=Notification::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private ?Notification $notification;

    /**
     * @ORM\OneToMany(targetEntity=InquiringRating::class, mappedBy="supplier")
     */
    private Collection $ratings;

    public function __construct()
    {
        $this->inquiries = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone($phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->emailVerifiedAt;
    }

    /**
     * @param ?DateTime $emailVerifiedAt
     * @return User
     */
    public function setEmailVerifiedAt(?DateTime $emailVerifiedAt): self
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getLastEmailVerificationTry(): ?DateTime
    {
        return $this->lastEmailVerificationTry;
    }

    /**
     * @param ?DateTime $lastEmailVerificationTry
     * @return User
     */
    public function setLastEmailVerificationTry(?DateTime $lastEmailVerificationTry): User
    {
        $this->lastEmailVerificationTry = $lastEmailVerificationTry;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(UserRole $role)
    {
        $this->roles[] = $role->value;
    }

    public function removeRole(UserRole $role)
    {
        $itemKey = array_search($role->value, $this->roles);

        // Remove the item if exists
        if ($itemKey >= 0) {
            unset($this->roles[$itemKey]);
            // reindex the array.
            $this->roles = array_values($this->roles);
        }
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getInquiries(): Collection
    {
        return $this->inquiries;
    }

    public function addInquiry(Inquiry $inquiry): self
    {
        if (!$this->inquiries->contains($inquiry)) {
            $this->inquiries[] = $inquiry;
            $inquiry->setAuthor($this);
        }

        return $this;
    }

    public function removeInquiry(Inquiry $inquiry): self
    {
        if ($this->inquiries->removeElement($inquiry)) {
            // set the owning side to null (unless already changed)
            if ($inquiry->getAuthor() === $this) {
                $inquiry->setAuthor(null);
            }
        }

        return $this;
    }

    public function getType(): UserType
    {
        return $this->type;
    }

    public function setType(UserType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function isType(UserType $type): bool
    {
        return $this->getType() == $type;
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     * @return User
     */
    public function setProfile(Profile $profile): User
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setAuthor($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getAuthor() === $this) {
                $offer->setAuthor(null);
            }
        }

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(Notification $notification): self
    {
        // set the owning side of the relation if necessary
        if ($notification->getUser() !== $this) {
            $notification->setUser($this);
        }

        $this->notification = $notification;

        return $this;
    }

    /**
     * @return Collection<int, InquiringRating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(InquiringRating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setSupplier($this);
        }

        return $this;
    }

    public function removeRating(InquiringRating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getSupplier() === $this) {
                $rating->setSupplier(null);
            }
        }

        return $this;
    }
}
