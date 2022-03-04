<?php

namespace App\Business\Service;

use App\Entity\AEntity;
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

    /**
     * @param Subscription $entity
     * @return bool
     */
    public function update(AEntity $entity): bool
    {
        // Clear inquiries to be sent if newsletter is turned off.
        if (!$entity->getNewsletter()) {
            $entity->clearInquiries();
        }

        return parent::update($entity);
    }

    /**
     * Returns all active subscriptions.
     * @param array $ordering
     * @return Subscription[]
     */
    public function findActiveSubscriptions(array $ordering = []): array
    {
        return $this->repository->findBy(["newsletter" => true], $ordering);
    }
}