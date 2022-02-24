<?php

namespace App\Controller\Auth;

use App\Controller\AController;
use App\Enum\FlashMessageType;
use App\Form\Auth\LoginForm;
use App\Security\UserSecurity;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AController
{
    public function __construct(
        private UserSecurity        $security,
        private AuthenticationUtils $authenticationUtils,
        private TranslatorInterface $translator
    )
    {
    }

    public function login(): Response
    {
        // TODO: flash message "please log in to access this page" when "next" URL is set.

        // TODO: add param "logout" - if "lougout=1 then the user is logged out.

        // Check if the user is already logged in
        if ($this->security->isLoggedIn()) {
            $this->addFlashMessage(FlashMessageType::NOTICE, $this->translator->trans("auth.already_logged_in"));

            return $this->redirectToRoute("home");
        }

        // Create a login form.
        $form = $this->createForm(LoginForm::class);
        $form->get('_username')->setData($this->authenticationUtils->getLastUsername());
        // TODO: set "remember_me" too

        // Login errors
        if ($error = $this->authenticationUtils->getLastAuthenticationError()) {
            $form->addError(new FormError($this->translator->trans($error->getMessageKey())));
        }

        return $this->renderForm('auth/login.html.twig', [
            'form' => $form
        ]);
    }
}
