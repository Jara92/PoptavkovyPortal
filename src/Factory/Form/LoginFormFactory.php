<?php

namespace App\Factory\Form;

use App\Form\Auth\LoginForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\Translation\t;

class LoginFormFactory
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private AuthenticationUtils  $authenticationUtils,
        private TranslatorInterface  $translator
    )
    {
    }

    public function createLoginForm(): FormInterface
    {
        $form = $this->formFactory->create(LoginForm::class);
        $form->get('_username')->setData($this->authenticationUtils->getLastUsername());
        // TODO: set "remember_me" too

        if ($error = $this->authenticationUtils->getLastAuthenticationError()) {
            $form->addError(new FormError($this->translator->trans($error->getMessageKey())));
        }

        return $form;
    }
}