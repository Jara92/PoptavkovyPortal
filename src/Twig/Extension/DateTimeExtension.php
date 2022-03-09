<?php

namespace App\Twig\Extension;

use App\Entity\Inquiry\Inquiry;
use DateTime;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateTimeExtension extends AbstractExtension
{
    const TODAY = 24 * 60 * 60;
    const YESTERDAY = 2 * 24 * 60 * 60;

    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('date_pretty', [$this, "datePretty"]),
            new TwigFilter('inquiry_date', [$this, "inquiryDate"]),
        ];
    }

    /**
     * Returns formatted date.
     * Returns special values for today, yesterday...
     * @param DateTime $date
     * @return string
     */
    public function datePretty(DateTime $date): string
    {
        $dateTimeStamp = $date->getTimestamp();
        $nowTimeStamp = (new DateTime())->getTimestamp();

        // Get the difference between $date and now.
        $diff = $nowTimeStamp - $dateTimeStamp;

        // Today
        if ($diff <= self::TODAY) {
            return $this->translator->trans("date.today");
        } // Yesterday
        else if ($diff <= self::YESTERDAY) {
            return $this->translator->trans("date.yesterday");
        }

        // Default
        return $date->format("j.n.Y"); // Show time? H:m?
    }

    /**
     * Returns inquiry creation/publication formatted date.
     * @param Inquiry $inquiry
     * @return string
     */
    public function inquiryDate(Inquiry $inquiry): string
    {
        if ($inquiry->getPublishedAt()) {
            return $this->datePretty($inquiry->getPublishedAt());
        }

        return $this->datePretty($inquiry->getCreatedAt());
    }
}