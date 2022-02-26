<?php

namespace App\Controller;

use App\Business\Operation\SubscriptionOperation;
use App\Business\Operation\UserOperation;
use App\Business\Service\SubscriptionService;
use App\Enum\FlashMessageType;
use App\Form\Inquiry\SubscriptionForm;
use App\Security\UserSecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionController extends AController
{
    public function __construct(
        private SubscriptionOperation $subscriptionOperation,
        private UserOperation         $userOperation,
        private SubscriptionService   $subscriptionService,
        private TranslatorInterface   $translator
    )
    {
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
}