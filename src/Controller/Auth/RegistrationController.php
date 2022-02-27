<?php

namespace App\Controller\Auth;

use App\Business\Operation\UserOperation;
use App\Controller\AController;
use App\Entity\User;
use App\Enum\Entity\UserType;
use App\Enum\FlashMessageType;
use App\Factory\UserFactory;
use App\Form\Auth\RegisterCompanyForm;
use App\Form\Auth\RegisterPersonForm;
use App\Security\EmailVerifier;
use App\Security\UserSecurity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AController
{
    const AFTER_REGISTRATION_REDIRECT = 'app_login';

    public function __construct(
        private UserOperation       $userOperation,
        private UserFactory         $userFactory,
        private TranslatorInterface $translator,
        private EmailVerifier       $emailVerifier,
        private UserSecurity        $security
    )
    {
    }

    public function index(): Response
    {
        // Check if the user is already logged in
        if ($this->security->isLoggedIn()) {
            $this->addFlashMessage(FlashMessageType::NOTICE, $this->translator->trans("auth.already_logged_in"));

            return $this->redirectToRoute("home");
        }

        return $this->render('auth/register.html.twig');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function registerPerson(Request $request): Response
    {
        $user = $this->userFactory->createBlank();

        // Set user type.
        $user->setType(UserType::PERSON);

        $form = $this->createForm(RegisterPersonForm::class, $user);

        return $this->register($form, $user, $request, "auth/register_person.html.twig");
    }

    public function registerCompany(Request $request): Response
    {
        $user = $this->userFactory->createBlank();

        // Set user type.
        $user->setType(UserType::COMPANY);

        $form = $this->createForm(RegisterCompanyForm::class, $user);

        return $this->register($form, $user, $request, "auth/register_company.html.twig");
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function register(FormInterface $form, User $user, Request $request, $template): Response
    {
        // Check if the user is already logged in
        if ($this->security->isLoggedIn()) {
            $this->addFlashMessage(FlashMessageType::NOTICE, $this->translator->trans("auth.already_logged_in"));

            return $this->redirectToRoute("home");
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get blank password and register the user.
            $blankPassword = $form->get('plainPassword')->getData();

            if ($this->userOperation->register($user, $blankPassword)) {
                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("auth.msg_successfully_registred"));

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user);
            }

            return $this->redirectToRoute(self::AFTER_REGISTRATION_REDIRECT);
        }

        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
