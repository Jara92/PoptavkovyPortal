<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\ProfileService;
use App\Business\Service\UserService;
use App\Form\User\ProfileForm;
use App\Helper\FlashHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

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

            $this->addFlash(FlashHelper::SUCCESS, $this->translator->trans("profiles.msg_information_updated"));
        }

        return $this->renderForm("user/settings/profile_settings.html.twig", ["form" => $form]);
    }
}