<?php

namespace App\Business\Operation;

use App\Business\Service\SubscriptionService;
use App\Factory\Inquiry\SubscriptionFactory;

class SubscriptionOperation
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private SubscriptionFactory $subscriptionFactory
    )
    {
    }
}