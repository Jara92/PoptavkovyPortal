<?php

namespace App\Controller;

use App\Business\Operation\SubscriptionOperation;
use App\Business\Operation\UserOperation;
use App\Business\Service\ProfileService;
use App\Business\Service\SubscriptionService;
use App\Business\Service\UserService;
use App\Enum\FlashMessageType;
use App\Exception\InvalidOldPasswordException;
use App\Exception\OperationFailedException;
use App\Form\Auth\ChangePasswordForm;
use App\Form\Inquiry\SubscriptionForm;
use App\Form\User\ProfileForm;
use App\Twig\Extension\UserExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class AccountSettingsController extends AController
{
    public function __construct(
        private UserOperation       $userOperation,
        private UserService         $userService,
        private SubscriptionService $subscriptionService,
        private TranslatorInterface $translator,
        private Breadcrumbs         $breadcrumbs,
        private RouterInterface     $router,
    )
    {
        $this->breadcrumbs->addItem("mainnav.home", $this->router->generate("home"));
        $this->breadcrumbs->addItem("user.my_account");
    }

    /**
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function editMyProfile(Request $request): Response
    {
        $profile = $this->getUser()->getProfile();
        $this->denyAccessUnlessGranted("edit", $profile);
        $this->breadcrumbs->addItem("profiles.btn_edit_profile");

        $form = $this->createForm(ProfileForm::class, $profile);

        // Handle form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get("avatar")->getData();

            $this->userOperation->updateProfile($profile, $avatar);

            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("profiles.msg_information_updated"));
        }

        return $this->renderForm("user/settings/profile_settings.html.twig", ["form" => $form]);
    }

    /**
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_SUPPLIER")
     */
    public function editMySubscriptions(Request $request): Response
    {
        $subscription = $this->getUser()->getSubscription();

        // This should not happen
        if (!$subscription) {
            throw new \LogicException("You are not allowed to edit inquiry subscription. :(");
        }

        $form = $this->createForm(SubscriptionForm::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->subscriptionService->createOrUpdate($subscription);

            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("subscriptions.msg_settings_saved"));
        }

        return $this->renderForm("user/settings/inquiry_subscription.html.twig", ["form" => $form]);
    }

    /**
     * Action to change user's password
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(Request $request): Response
    {
        $this->breadcrumbs->addItem("auth.password_change");

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

    /**
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function myProfile()
    {
        return $this->render("user/base.html.twig");
    }

    /**
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function baseSettings(Request $request)
    {
        $this->breadcrumbs->addItem("profiles.settings_basic");

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

            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("profiles.msg_information_updated"));
        }

        return $this->renderForm("user/settings/base_settings.html.twig", ["form" => $form]);
    }
}