<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;
use App\Enum\FlashMessageType;
use App\Exception\InvalidOldPasswordException;
use App\Exception\OperationFailedException;
use App\Form\Auth\ChangePasswordForm;
use App\Form\User\UserSettingsForm;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AController
{
    public function __construct(
        private UserOperation       $userOperation,
        private UserService         $userService,
        private TranslatorInterface $translator,
    )
    {
    }

    /**
     * Action to change user's password
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        // Get user and check if is valid.
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException();
        }

        // Create a new ChangePasswordForm
        $form = $this->createForm(ChangePasswordForm::class);

        // Handle form request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get old and new password from the form
            $oldPass = $form->get('oldPassword')->getData();
            $newPass = $form->get('newPassword')->getData();

            // Try to update the password
            try {
                $this->userOperation->updateUserPassword($user, $oldPass, $newPass);
                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("auth.msg_password_changed"));
            } catch (InvalidOldPasswordException $ex) {
                $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans("auth.msg_old_password_incorrect"));
            } catch (OperationFailedException $ex) {
                $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans("auth.msg_password_not_changed"));
            }
        }

        return $this->renderForm("user/settings/change_password.html.twig", ["form" => $form]);
    }

    public function myProfile()
    {
        return $this->render("user/base.html.twig");
    }

    public function baseSettings(Request $request)
    {
        // Get blank inquiry
        $user = $this->getUser();

        // Check permissions
        $this->denyAccessUnlessGranted("edit", $user);

        // Create inquiry form
        $form = $this->createForm($this->userOperation->getUserSettingsFormClass($user), $user);

        // Handle form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the inquiry.
            $this->userService->update($user);

            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("profiles.information_updated"));
        }

        return $this->renderForm("user/settings/base_settings.html.twig", ["form" => $form]);
    }
}