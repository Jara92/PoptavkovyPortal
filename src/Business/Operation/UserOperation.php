<?php

namespace App\Business\Operation;

use App\Business\Service\UserService;
use App\Entity\User;
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

    public function __construct(UserService $userService, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier){
        $this->userService = $userService;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
    }

    public function register(User $user, string $blankPassword)
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $blankPassword);

        $now = new \DateTime();
        $user->setCreatedAt($now)->setUpdatedAt($now);

        $user->setPassword($passwordHash);

        if($this->userService->create($user)){
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('info@poptejsi.cz', 'Poptejsi.cz'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
        }
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function verifyEmail($data, User $user){
        // validate email confirmation link, sets User::isVerified=true and persists
            $this->emailVerifier->handleEmailConfirmation($data, $user);

            $user->setEmailVerifiedAt(new \DateTime());
            $this->userService->update($user);
    }
}