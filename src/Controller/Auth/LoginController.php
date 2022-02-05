<?php

namespace App\Controller\Auth;

use App\Business\Service\UserService;
use App\Factory\LoginFormFactory;
use App\Helper\FlashHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AbstractController
{
    public function login(LoginFormFactory $loginFormFactory, UserService $userService, TranslatorInterface $translator): Response
    {
        // Check if the user is already logged in
        if($userService->isLoggedIn()){
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
