<?php

namespace App\Business\Service\Inquiry;

use App\Business\SmartTag\ISmartTag;
use App\Business\SmartTag\NoOffersYet;
use App\Business\SmartTag\WithAttachments;

class SmartTagService
{
    /** @var ISmartTag[] */
    private array $registredTags;

    public function __construct()
    {
        $this->registredTags = [
            new NoOffersYet(),
            new WithAttachments()
        ];
    }

    /**
     * Returns all registered smart tags.
     * @return ISmartTag[]
     */
    public function readAll(): array
    {
        return $this->registredTags;
    }
}