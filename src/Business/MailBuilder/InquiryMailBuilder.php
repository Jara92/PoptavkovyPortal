<?php

namespace App\Business\MailBuilder;

use App\Entity\Inquiry\Inquiry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Builder for inquiries emails.
 */
class InquiryMailBuilder
{
    public function __construct(protected TranslatorInterface $translator, protected ContainerBagInterface $params)
    {
    }

    /**
     * Notification of publication of the inquiry.
     * @param Inquiry $inquiry
     * @return TemplatedEmail
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function notifyPublishedInquiry(Inquiry $inquiry): TemplatedEmail
    {
        // Build and send the email
        return (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($inquiry->getContactEmail())
            ->subject($this->translator->trans("inquiries.inquiry_published"))
            ->htmlTemplate('email/inquiry/publish_notify.html.twig')
            ->context(["inquiry" => $inquiry]);
    }

    public function notifyDeletedInquiry(Inquiry $inquiry): TemplatedEmail
    {
        // Build and send the email
        return (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($inquiry->getContactEmail())
            ->subject($this->translator->trans("inquiries.inquiry_deleted"))
            ->htmlTemplate('email/inquiry/delete_notify.html.twig')
            ->context(["inquiry" => $inquiry]);
    }
}