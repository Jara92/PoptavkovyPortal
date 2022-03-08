<?php

namespace App\Business\Service;

use App\Business\SmartTag\ISmartTag;

class SmartTagService
{
    /** @var ISmartTag[] */
    private array $registredTags;

    public function __construct()
    {
        $this->registredTags = [

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