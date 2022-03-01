<?php

namespace App\Twig\Extension;

use App\Entity\Inquiry\Inquiry;
use App\Enum\Entity\InquiryType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class InquiryExtension extends AbstractExtension
{
    public function __construct(
        private TranslatorInterface $translator,
        private UserExtension       $userExtension
    )
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('inquiry_anonymize_author', [$this, 'anonymizeAuthor']),
            new TwigFilter('inquiry_location', [$this, 'location']),
        ];
    }

    /**
     * Returs anonymized inquiry author.
     * @param Inquiry $inquiry
     * @return string
     */
    public function anonymizeAuthor(Inquiry $inquiry): string
    {
        // Anonymize user object is the inquiry has an author.
        if ($inquiry->getAuthor()) {
            return $this->userExtension->anonymize($inquiry->getAuthor());
        }

        switch ($inquiry->getType()) {
            case InquiryType::PERSONAL:
                return $inquiry->getPersonalContact()->getName() . " " . $inquiry->getPersonalContact()->getSurname()["0"] . ".";
            case InquiryType::COMPANY:
                if ($inquiry->getCity()) {
                    $location = $inquiry->getCity();
                } else if ($inquiry->getRegion()) {
                    $location = $inquiry->getRegion()->getTitle();
                } else {
                    $location = $this->translator->trans("user.company_from_default");
                }

                return $this->translator->trans("user.company_from") . " " . $location;
            default:
                throw new \LogicException("Invalid inquiry type: " . $inquiry->getType()->value);
        }
    }

    /**
     * Returns inquiry location.
     * @param Inquiry $inquiry
     * @return string
     */
    public function location(Inquiry $inquiry): string
    {
        if ($inquiry->getRegion()) {
            $location = $inquiry->getRegion()->getTitle();

            if ($inquiry->getCity()) {
                $location .= ", " . $inquiry->getCity();
            }

            return $location;
        }

        return $this->translator->trans("inquiries.field_region_ph");
    }

}