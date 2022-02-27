<?php

namespace App\Controller\Auth;

use App\Business\Service\UserService;
use App\Controller\AController;
use App\Enum\Entity\UserRole;
use App\Enum\FlashMessageType;
use App\Exception\UserAlreadyVerifiedException;
use App\Exception\VerificationEmailResendShortDelayException;
use App\Form\Auth\UserVerificationForm;
use App\Security\EmailVerifier;
use App\Security\UserSecurity;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserVerificationController extends AController
{
    public function __construct(
        private UserService         $userService,
        private UserSecurity        $security,
        private EmailVerifier       $emailVerifier,
        private TranslatorInterface $translator,
    )
    {
    }

    /**
     * Display and handle form for resending a verification email.
     * @param Request $request
     * @return Response
     */
    public function verification(Request $request): Response
    {
        // Check if the user is already logged in
        if ($this->security->isLoggedIn()) {
            $this->addFlashMessage(FlashMessageType::NOTICE, $this->translator->trans("auth.already_logged_in"));

            return $this->redirectToRoute("home");
        }

        $form = $this->createForm(UserVerificationForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get("email")->getData();

            // Try to get user by given email.
            $user = $this->userService->readByEmail($email);

            // Try to resend verification.
            try {
                $this->emailVerifier->resendEmailConfirmation("app_verify_email", $user);
                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("auth.msg_verification_email_resend_success"));

                $form = $this->createForm(UserVerificationForm::class);
            } catch (UserNotFoundException $ex) {
                $form->addError(new FormError($this->translator->trans("auth.email_not_registered")));
            } catch (UserAlreadyVerifiedException $ex) {
                $form->addError(new FormError($this->translator->trans("auth.user_already_verified")));
            } catch (VerificationEmailResendShortDelayException $ex) {
                $form->addError(new FormError($this->translator->trans(
                    "auth.verification_email_resend_short_delay", ["%seconds%" => $ex->getRemainingDelay()])));
            }
        }

        return $this->renderForm("auth/resend_verification.html.twig", ["form" => $form]);
    }
}
