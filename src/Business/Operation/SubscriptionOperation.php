<?php

namespace App\Business\Operation;

use App\Business\Service\SubscriptionService;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\Subscription;
use App\Factory\Inquiry\SubscriptionFactory;

class SubscriptionOperation
{
    public function __construct(
        private SubscriptionService $subscriptionService,
    )
    {
    }

    /**
     * Is given inquiry relevant for given subscription?
     * @param Inquiry $inquiry
     * @param Subscription $subscription
     * @return bool
     */
    private function isInquiryRelevant(Inquiry $inquiry, Subscription $subscription): bool
    {
        // TODO: Optimize this method

        // Categories filter
        $commonCategories = array_intersect($inquiry->getCategories()->toArray(), $subscription->getCategories()->toArray());

        // regions filter
        $regions = true;

        // Inquiry has a region set and subscription has at least one region set.
        if ($inquiry->getRegion() && !empty($subscription->getRegions())) {
            $regions = in_array($inquiry->getRegion(), $subscription->getRegions()->toArray());
        }

        // types
        $types = true;

        // Subscription has at least one type set.
        if (!empty($subscription->getTypes())) {
            $types = in_array($inquiry->getType(), $subscription->getTypes());
        }

        if (count($commonCategories) > 0 && $regions && $types) {
            return true;
        }

        return false;
    }

    /**
     * Process new inquiry subscriptions.
     * @param Inquiry $inquiry
     */
    public function handleNewInquiry(Inquiry $inquiry)
    {
        $activeSubscriptions = $this->subscriptionService->findActiveSubscriptions();

        // For each active subscription check whether the inquiry is relevant.
        foreach ($activeSubscriptions as $subscription) {
            if ($this->isInquiryRelevant($inquiry, $subscription)) {
                $subscription->addInquiry($inquiry);
            }

            $this->subscriptionService->update($subscription);
        }
    }
}