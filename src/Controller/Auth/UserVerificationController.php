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
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserVerificationController extends AController
{
    const AFTER_VERIFY_ERROR_REDIRECT = 'home';
    const AFTER_VERIFY = "app_login";

    public function __construct(
        private UserService         $userService,
        private UserSecurity        $security,
        private EmailVerifier       $emailVerifier,
        private TranslatorInterface $translator,
    )
    {
    }

    public function verifyUserEmail(Request $request): Response
    {
        $id = $request->get('id');

        // Invalid ID
        if ($id == null) {
            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

        // Check if exists a user with the given $id.
        $user = $this->userService->readById($id);
        if ($user == null) {
            $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans("auth.user_not_registered"));
            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

        // Validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans('auth.successfully_verified'));

            return $this->redirectToRoute(self::AFTER_VERIFY);
        } // Link expired - display warning and redirect verification email generating form.
        catch (ExpiredSignatureException $exception) {
            // Show flash notification and redirect to new link generation form.
            $this->addFlashMessage(FlashMessageType::WARNING, $this->translator->trans("auth.msg_link_expired"));
            return $this->redirectToRoute("app_verify_form");
        } // User is already vefified - display warning and redirect to login page
        catch (UserAlreadyVerifiedException $exception) {
            $this->addFlashMessage(FlashMessageType::WARNING, $this->translator->trans("auth.user_already_verified"));
            return $this->redirectToRoute("app_login");
        } // Other verification exceptions.
        catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans($exception->getReason()));
        }

        return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
    }

    /**
     * Display and handle form for resending a verification email.
     * @param Request $request
     * @return Response
     */
    public function resendVerification(Request $request): Response
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
