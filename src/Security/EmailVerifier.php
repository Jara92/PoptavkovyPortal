<?php

namespace App\Security;

use App\Business\Service\UserService;
use App\Entity\User;
use App\Enum\Entity\UserRole;
use App\Exception\UserAlreadyVerifiedException;
use App\Exception\VerificationEmailResendShortDelayException;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private UserService                $userService,
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface            $mailer,
        private TranslatorInterface        $translator,
        private ContainerBagInterface      $params,
    )
    {
    }

    protected function createVerificationEmail(User $user): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address('info@poptejsi.cz', 'Poptejsi.cz'))
            ->to($user->getEmail())
            ->subject($this->translator->trans("auth.confirm_your_email"))
            ->htmlTemplate('email/auth/confirmation.html.twig');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $email = $this->createVerificationEmail($user);

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws VerificationEmailResendShortDelayException
     * @throws TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function resendEmailConfirmation(string $verifyEmailRouteName, ?User $user): void
    {
        if (!$user) {
            throw new UserNotFoundException();
        }

        // Already verified?
        if ($user->isVerified()) {
            throw new UserAlreadyVerifiedException("This user is already verified!");
        }

        // has the given time elapsed since the last attempt?
        $now = new DateTime();
        $lastVerificationTry = $user->getLastEmailVerificationTry();
        if ($lastVerificationTry) {
            $delay = $this->params->get("app.verification_resend_delay");

            // Calculate difference between now and the last try.
            $diff = $now->getTimestamp() - $lastVerificationTry->getTimestamp();
            if ($diff < $delay) {
                throw new VerificationEmailResendShortDelayException($delay - $diff, "Delay too short!");
            }
        }

        $this->sendEmailConfirmation($verifyEmailRouteName, $user);

        // Update lastEmailVerificationTry
        $user->setLastEmailVerificationTry($now);
        $this->userService->update($user);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        // Check if user is already verified
        if ($user->isVerified()) {
            throw new UserAlreadyVerifiedException("This accont is already verified");
        }

        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);
        $user->setEmailVerifiedAt(new DateTime());
        $user->addRole(UserRole::VERIFIED);

        $this->userService->update($user);
    }
}
