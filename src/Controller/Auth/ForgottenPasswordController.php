<?php

namespace App\Controller\Auth;

use App\Controller\AController;
use App\Form\Auth\ForgottenPasswordForm;
use Symfony\Component\HttpFoundation\Request;

class ForgottenPasswordController extends AController
{
    public function recover(Request $request)
    {
        $form = $this->createForm(ForgottenPasswordForm::class);

        return $this->renderForm("auth/forgotten_password.html.twig", ["form" => $form]);
    }
}