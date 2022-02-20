<?php

namespace App\Controller\Auth;

use App\Factory\Form\LoginFormFactory;
use App\Helper\FlashHelper;
use App\Security\UserSecurity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AbstractController
{
    public function login(LoginFormFactory $loginFormFactory, UserSecurity $security, TranslatorInterface $translator): Response
    {
        // TODO: flash message "please log in to access this page" when "next" URL is set.

        // Check if the user is already logged in
        if($security->isLoggedIn()){
            $this->addFlash(FlashHelper::NOTICE, $translator->trans("auth.already_logged_in"));

            return $this->redirectToRoute("home");
        }

        // Create a login form.
        $form = $loginFormFactory->createLoginForm();

        return $this->renderForm('auth/login.html.twig', [
            'form' => $form
        ]);
    }
}
