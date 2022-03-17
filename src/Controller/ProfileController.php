<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\ProfileService;
use App\Business\Service\RatingService;
use App\Business\Service\UserService;
use App\Entity\Profile;
use App\Entity\User\UserRating;
use App\Enum\Entity\UserType;
use App\Enum\FlashMessageType;
use App\Form\User\ProfileForm;
use App\Form\User\UserRatingForm;
use App\Twig\Extension\UserExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
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
        private ProfileService      $profileService,
        private RatingService       $ratingService,
        private TranslatorInterface $translator,
        private Breadcrumbs         $breadcrumbs,
        private RouterInterface     $router,
        private UserExtension       $userExtension
    )
    {
        $this->breadcrumbs->addItem("mainnav.home", $this->router->generate("home"));
        $this->breadcrumbs->addItem("profiles.profiles");
    }

    private function handleRatingForm(Request $request, Profile $profile): ?FormInterface
    {
        if ($this->getUser()) {
            $rating = ($this->getUser()) ? (new UserRating())->setTarget($profile->getUser())->setAuthor($this->getUser()) : null;
            $form = $this->createForm(UserRatingForm::class, $rating);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->ratingService->create($rating);

                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("ratings.msg_rating_sent"));
            }

            return $form;
        }

        return null;
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

        $form = $this->handleRatingForm($request, $profile);

        switch ($profile->getUser()->getType()) {
            case UserType::PERSON:
                return $this->personProfileDetail($profile, $form);
            case UserType::COMPANY:
                return $this->companyProfileDetail($profile, $form);
            default:
                throw new \HttpRequestException("Invalid profile type.");
        }
    }

    private function personProfileDetail(Profile $profile, ?FormInterface $form): Response
    {
        $this->breadcrumbs->addItem($this->userExtension->anonymize($profile->getUser()));

        return $this->renderForm("profile/detail_person.html.twig", ["profile" => $profile, "form" => $form]);
    }

    private function companyProfileDetail(Profile $profile, ?FormInterface $form): Response
    {
        $this->breadcrumbs->addItem($this->userExtension->fullName($profile->getUser()));

        return $this->renderForm("profile/detail_company.html.twig", ["profile" => $profile, "form" => $form]);
    }
}