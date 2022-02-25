<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Subscription;
use App\Repository\Interfaces\Inquiry\ISubscriptionRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<Subscription, int>
 */
class SubscriptionService extends AService
{
    /** @var ISubscriptionRepository */
    protected IRepository $repository;

    public function __construct(ISubscriptionRepository $subscriptionRepository)
    {
        parent::__construct($subscriptionRepository);
    }
}