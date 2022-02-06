<?php

namespace App\Controller\Auth;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;
use App\Business\Service\UserTypeService;
use App\Entity\User;
use App\Entity\UserType;
use App\Factory\UserFactory;
use App\Form\RegisterCompanyForm;
use App\Form\RegisterPersonForm;
use App\Helper\FlashHelper;
use App\Security\EmailVerifier;
use App\Security\UserSecurity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    protected UserOperation $userOperation;

    protected UserService $userService;

    protected UserFactory $userFactory;

    protected TranslatorInterface $translator;

    /** @required */
    public EmailVerifier $emailVerifier;

    /** @required */
    public UserTypeService $userTypeService;

    /** @required */
    public UserSecurity $security;

    const AFTER_REGISTRATION_REDIRECT = 'inquiries';
    const AFTER_VERIFY_ERROR_REDIRECT = 'app_register';
    const AFTER_VERIFY = "inquiries";

    public function __construct(UserOperation $userOperation, UserService $userService, UserFactory $userFactory,
                                TranslatorInterface $translator)
    {
        $this->userOperation = $userOperation;
        $this->userService = $userService;
        $this->userFactory = $userFactory;
        $this->translator = $translator;
    }

    public function index():Response{
        // Check if the user is already logged in
        if($this->security->isLoggedIn()){
            $this->addFlash(FlashHelper::NOTICE, $this->translator->trans("auth.already_logged_in"));

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
        $user->setType($this->userTypeService->readByAlias(UserType::TYPE_PERSONAL));

        $form = $this->createForm(RegisterPersonForm::class, $user);

        return $this->register($form, $user, $request, "auth/register_person.html.twig");
    }

    public function registerCompany(Request $request): Response
    {
        $user = $this->userFactory->createBlank();

        // Set user type.
        $user->setType($this->userTypeService->readByAlias(UserType::TYPE_COMPANY));

        $form = $this->createForm(RegisterCompanyForm::class, $user);

        return $this->register($form, $user, $request, "auth/register_company.html.twig");
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function register(FormInterface $form, User $user, Request $request, $template) : Response{
        // Check if the user is already logged in
        if($this->security->isLoggedIn()){
            $this->addFlash(FlashHelper::NOTICE, $this->translator->trans("auth.already_logged_in"));

            return $this->redirectToRoute("home");
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get blank password and register the user.
            $blankPassword = $form->get('plainPassword')->getData();

            dump($user);

            if ($this->userOperation->register($user, $blankPassword)) {
                $this->addFlash('success', "auth.successfully_registred");

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

        if (is_null($id)) {
            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

        $user = $this->userService->readById($id);

        if (is_null($user)) {
            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

        // Validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

        // TODO: Flash messages.
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute(self::AFTER_VERIFY);
    }
}
