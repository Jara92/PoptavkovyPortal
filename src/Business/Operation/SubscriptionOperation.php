<?php

namespace App\Business\Operation;

use App\Business\Service\SubscriptionService;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\Subscription;
use App\Factory\Inquiry\SubscriptionFactory;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionOperation
{
    public function __construct(
        private SubscriptionService   $subscriptionService,
        private MailerInterface       $mailer,
        private ContainerBagInterface $params,
        private TranslatorInterface   $translator
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
        if ($inquiry->getRegion() && !$subscription->getRegions()->isEmpty()) {
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws TransportExceptionInterface
     * @returns int The number of emails sent.
     */
    public function sendNewInquiries(): int
    {
        $activeSubscriptions = $this->subscriptionService->findActiveSubscriptions();
        $sendEmails = 0;

        // For each active subscription check whether the inquiry is relevant.
        foreach ($activeSubscriptions as $subscription) {
            // Send email only if there are any inquries to be send.
            if (!$subscription->getInquiries()->isEmpty()) {
                $this->sendSubscriptionEmail($subscription);
                $sendEmails++;

                $subscription->clearInquiries();
                $this->subscriptionService->update($subscription);
            }
        }

        return $sendEmails;
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function sendSubscriptionEmail(Subscription $subscription): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($subscription->getUser()->getEmail())
            ->subject($this->translator->trans("inquiries.new_inquiries_newsletter"))
            ->htmlTemplate('email/inquiry/newsletter.html.twig')
            ->context(["subscription" => $subscription]);

        $this->mailer->send($email);
    }
}