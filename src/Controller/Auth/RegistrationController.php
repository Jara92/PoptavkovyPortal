<?php

namespace App\Controller\Auth;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;
use App\Controller\AController;
use App\Entity\User;
use App\Enum\Entity\UserType;
use App\Enum\FlashMessageType;
use App\Exception\UserAlreadyVerifiedException;
use App\Factory\UserFactory;
use App\Form\Auth\RegisterCompanyForm;
use App\Form\Auth\RegisterPersonForm;
use App\Security\EmailVerifier;
use App\Security\UserSecurity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AController
{
    const AFTER_REGISTRATION_REDIRECT = 'inquiries';
    const AFTER_VERIFY_ERROR_REDIRECT = 'home';
    const AFTER_VERIFY = "inquiries";

    public function __construct(
        private UserOperation       $userOperation,
        private UserService         $userService,
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

    public function verifyUserEmail(Request $request): Response
    {
        $id = $request->get('id');

        if ($id == null) {
            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

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
        } // Link expired exception - generate and send a new one.
        catch (ExpiredSignatureException $exception) {
            // Show flash notification and redirect to new link generation form.
            $this->addFlashMessage(FlashMessageType::WARNING, $this->translator->trans("auth.msg_link_expired"));
            return $this->redirectToRoute("app_verify_form");
        } catch (UserAlreadyVerifiedException $exception) {
            $this->addFlashMessage(FlashMessageType::WARNING, $this->translator->trans("auth.user_already_verified"));
            return $this->redirectToRoute("app_login");
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans($exception->getReason()));
        }

        return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
    }
}
