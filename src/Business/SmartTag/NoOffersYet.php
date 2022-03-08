<?php

namespace App\Business\SmartTag;

use App\Entity\Inquiry\Inquiry;

/**
 * This tags is attached to the inquiries without an offer.
 */
class NoOffersYet implements ISmartTag
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return "smart_tag.no_offers_yet";
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return "no_offers_yet";
    }

    /**
     * @inheritDoc
     */
    public function correspondsTo(Inquiry $inquiry): bool
    {
        // An inquiry has this tags only if it has no offers yet.
        if ($inquiry->getOffers()->isEmpty()) {
            return true;
        }

        return false;
    }
}