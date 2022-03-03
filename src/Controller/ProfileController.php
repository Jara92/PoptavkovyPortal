<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\ProfileService;
use App\Business\Service\UserService;
use App\Entity\Profile;
use App\Enum\Entity\UserType;
use App\Enum\FlashMessageType;
use App\Form\User\ProfileForm;
use App\Twig\Extension\UserExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ProfileController extends AController
{
    public function __construct(
        private UserOperation       $userOperation,
        private UserService         $userService,
        private ProfileService      $profileService,
        private TranslatorInterface $translator,
        private Breadcrumbs         $breadcrumbs,
        private RouterInterface     $router,
        private UserExtension       $userExtension
    )
    {
        $this->breadcrumbs->addItem("mainnav.home", $this->router->generate("home"));
        $this->breadcrumbs->addItem("profiles.profiles");
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

        // Notice user that he viewed a private profile.
        if (!$profile->getIsPublic()) {
            $this->addFlashMessage(FlashMessageType::NOTICE, $this->translator->trans("profiles.msg_profile_is_private"));
        }

        switch ($profile->getUser()->getType()) {
            case UserType::PERSON:
                return $this->personProfileDetail($profile);
            case UserType::COMPANY:
                return $this->companyProfileDetail($profile);
            default:
                throw new \HttpRequestException("Invalid profile type.");
        }
    }

    private function personProfileDetail(Profile $profile): Response
    {
        $this->breadcrumbs->addItem($this->userExtension->anonymize($profile->getUser()));

        return $this->render("profile/detail_person.html.twig", ["profile" => $profile]);
    }

    private function companyProfileDetail(Profile $profile): Response
    {
        $this->breadcrumbs->addItem($this->userExtension->fullName($profile->getUser()));

        return $this->render("profile/detail_company.html.twig", ["profile" => $profile]);
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
}