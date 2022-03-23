<?php

namespace App\Controller;

use App\Business\Operation\ProfileOperation;
use App\Business\Service\ProfileService;
use App\Business\Service\RatingService;
use App\Entity\Profile;
use App\Entity\User\UserRating;
use App\Enum\Entity\UserType;
use App\Enum\FlashMessageType;
use App\Form\User\UserRatingForm;
use App\Tools\Rating\ProfileRatingComponent;
use App\Twig\Extension\UserExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ProfileController extends AController
{
    public function __construct(
        private ProfileOperation    $profileOperation,
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

    /**
     * Creates and handles rating form.
     * @param Request $request
     * @param Profile $profile
     * @return FormInterface|null
     */
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

    /**
     * Detail page for a user profile
     * @param int $profileId
     * @param Request $request
     * @return Response
     * @throws \InvalidArgumentException
     */
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

        // Build a form for user rating.
        $form = $this->handleRatingForm($request, $profile);

        // Get profile ratings.
        $rating = $this->profileOperation->getProfileRating($profile);

        // Display correct template according to user type
        switch ($profile->getUser()->getType()) {
            case UserType::PERSON:
                return $this->personProfileDetail($profile, $form, $rating);
            case UserType::COMPANY:
                return $this->companyProfileDetail($profile, $form, $rating);
            default:
                throw new InvalidArgumentException("Invalid profile type.");
        }
    }

    private function personProfileDetail(Profile $profile, ?FormInterface $form, ProfileRatingComponent $rating): Response
    {
        $this->breadcrumbs->addItem($this->userExtension->anonymize($profile->getUser()), translate: false);

        return $this->renderForm("profile/detail_person.html.twig", compact("profile", "form", "rating"));
    }

    private function companyProfileDetail(Profile $profile, ?FormInterface $form, ProfileRatingComponent $rating): Response
    {
        $this->breadcrumbs->addItem($this->userExtension->fullName($profile->getUser()), translate: false);

        return $this->renderForm("profile/detail_company.html.twig", compact("profile", "form", "rating"));
    }
}