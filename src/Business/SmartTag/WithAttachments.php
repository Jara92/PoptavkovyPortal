<?php

namespace App\Business\SmartTag;

use App\Entity\Inquiry\Inquiry;

class WithAttachments implements ISmartTag
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return "smart_tag.with_attachments";
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return "with_attachments";
    }

    /**
     * @inheritDoc
     */
    public function correspondsTo(Inquiry $inquiry): bool
    {
        // Tag is corresponding to an inquiry if the inquiry has at least one attachment.
        if ($inquiry->getAttachments()->isEmpty()) {
            return false;
        }

        return true;
    }
}