<?php

namespace App\Business\Operation;

use App\Business\Service\UserService;
use App\Business\Service\UserTypeService;
use App\Entity\User;
use App\Entity\UserType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserOperation
{
    protected $userService;

    protected $passwordHasher;

    /** @var EmailVerifier */
    protected $emailVerifier;

    /** @required */
    public UserTypeService $userTypeService;

    public function __construct(UserService $userService, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier)
    {
        $this->userService = $userService;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
    }

    public function registerPersonal(User $user, string $blankPassword): bool
    {
        // Set user type.
        $user->setType($this->userTypeService->readByAlias(UserType::TYPE_PERSONAL));

        // Set roles
        $user->addRole(User::ROLE_INQUIRING);

        return $this->register($user, $blankPassword);
    }

    public function register(User $user, string $blankPassword): bool
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $blankPassword);

        $user->setPassword($passwordHash);

        return $this->userService->create($user);
    }
}