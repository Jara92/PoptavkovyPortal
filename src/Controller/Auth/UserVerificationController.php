<?php

namespace App\Controller\Auth;

use App\Controller\AController;
use App\Form\Auth\UserVerificationForm;
use App\Security\UserSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserVerificationController extends AController
{
    public function __construct(
        private UserSecurity $security
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
        $form = $this->createForm(UserVerificationForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get("email")->getData();
        }

        return $this->renderForm("auth/resend_verification.html.twig", ["form" => $form]);
    }
}
