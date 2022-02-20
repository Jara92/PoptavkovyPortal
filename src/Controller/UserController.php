<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;
use App\Form\User\UserSettingsForm;
use App\Helper\FlashHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AController
{
    public function __construct(
        private UserOperation       $userOperation,
        private UserService         $userService,
        private TranslatorInterface $translator
    )
    {
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

            $this->addFlash(FlashHelper::SUCCESS, $this->translator->trans("profiles.information_updated"));
        }

        return $this->renderForm("user/settings/base_settings.html.twig", ["form" => $form]);
    }
}