<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    protected $userOperation;

    protected $userService;

    protected $userFactory;

    const AFTER_REGISTRATION_REDIRECT = 'inquiries';
    const AFTER_VERIFY_ERROR_REDIRECT = 'app_register';
    const AFTER_VERIFY = "inquiries";

    public function __construct(UserOperation $userOperation, UserService $userService, UserFactory $userFactory)
    {
        $this->userOperation = $userOperation;
        $this->userService = $userService;
        $this->userFactory = $userFactory;
    }

    public function register(Request $request): Response
    {
        $user = $this->userFactory->createBlank();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get blank password and register the user.
            $blankPassword = $form->get('plainPassword')->getData();
            $this->userOperation->register($user, $blankPassword);

            return $this->redirectToRoute(self::AFTER_REGISTRATION_REDIRECT);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function verifyUserEmail(Request $request, EmailVerifier $emailVerifier): Response
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
            $this->userOperation->verifyEmail($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute(self::AFTER_VERIFY_ERROR_REDIRECT);
        }

        // TODO: Flash messages.
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute(self::AFTER_VERIFY);
    }
}
