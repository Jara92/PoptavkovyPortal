<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\Region;
use App\Entity\Inquiry\Subscription;
use App\Enum\Entity\InquiryType;

class SubscriptionFactory
{
    /**
     * @param InquiryType[] $types
     * @param bool $newsletter
     * @param InquiryCategory[] $categories
     * @param Region[] $regions
     * @return Subscription
     */
    public function createSubscription(array $types, bool $newsletter, array $categories, array $regions): Subscription
    {
        $subscription = (new Subscription())->setNewsletter($newsletter)->setCategories($categories)->setRegions($regions);

        foreach ($types as $type) {
            $subscription->addType($type);
        }

        return $subscription;
    }
}