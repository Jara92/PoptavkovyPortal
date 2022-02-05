<?php

namespace App\Factory;

use App\Form\LoginForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginFormFactory
{
    protected FormFactoryInterface $formFactory;
    protected AuthenticationUtils $authenticationUtils;

    public function __construct(FormFactoryInterface $formFactory, AuthenticationUtils $authenticationUtils)
    {
        $this->formFactory = $formFactory;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function createLoginForm():FormInterface
    {
        $form = $this->formFactory->create(LoginForm::class);
        $form->get('_username')->setData($this->authenticationUtils->getLastUsername());
        // TODO: set "remember_me" too

        if ($error = $this->authenticationUtils->getLastAuthenticationError()) {
            $form->addError(new FormError($error->getMessage()));
        }

        return $form;
    }
}