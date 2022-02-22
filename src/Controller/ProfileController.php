<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\ProfileService;
use App\Business\Service\UserService;
use App\Enum\FlashMessageType;
use App\Form\User\ProfileForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

class ProfileController extends AController
{
    public function __construct(
        private UserOperation       $userOperation,
        private UserService         $userService,
        private ProfileService      $profileService,
        private TranslatorInterface $translator,
    )
    {
    }

    /**
     * @param int $profileId
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function edit(int $profileId, Request $request): Response
    {
        //  $profile = $this->getUser()->getProfile();
        //   $this->denyAccessUnlessGranted("edit", $profile);
        throw new MethodNotImplementedException("Not implemented yet.");
    }

    public function detail(int $profileId, Request $request): Response
    {
        // Get profile
        $profile = $this->profileService->readById($profileId);
        if (!$profile) {
            throw new NotFoundHttpException("Profile not found");
        }

        // Check permissions
        $this->denyAccessUnlessGranted("view", $profile);

        return $this->render("profile/detail.html.twig", ["profile" => $profile]);
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

        $form = $this->createForm(ProfileForm::class, $profile);

        // Handle form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the profile
            $this->profileService->update($profile);

            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("profiles.msg_information_updated"));
        }

        return $this->renderForm("user/settings/profile_settings.html.twig", ["form" => $form]);
    }
}